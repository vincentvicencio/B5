@extends('layouts.app') 

@section('content')
<!-- Include Bootstrap Icons CSS link -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

{{-- Main container hook for JS to identify the page --}}
<div class="py-6 sm:py-12" id="manage-index">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            
            {{-- HEADER: Use 'flex-column' for small screens, then 'flex-md-row' for medium screens and up --}}
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-5 header-content">
                <div class="mb-3 mb-md-0"> {{-- Add bottom margin for mobile, remove for medium screens and up --}}
                    <h2 class="h1 mb-3 fw-bold manage-title">Manage Assessment</h2>
                    <p class="text-muted fs-6 subtitle">Build and Refine Assessment Questionnaires</p>
                </div>
                {{-- Ensure the button is full-width on mobile or constrained --}}
                <a href="{{ route('manage.create') }}" class="btn btn-primary btn-add-trait w-md-auto">
                    <i class="bi bi-plus"></i> Add Trait
                </a>
            </div>

        @if (session('success'))
            <div class="alert alert-success trait-card animated-add">{{ session('success') }}</div>
        @endif
        
        <!-- Placeholder for non-blocking notifications / Empty message holder -->
        <div id="status-message" class="mb-1"></div>

        <div class="trait-list">
            @forelse ($traits as $trait)
            {{-- Card structure for each Trait --}}
            <div class="trait-card animated-add" data-trait-id="{{ $trait->id }}" style="border-left: 8px solid {{ $trait->trait_display_color ?? '#172c43' }};">
                
                <div class="trait-header"> 
                    <div class="trait-content">
                        <h3 class="trait-title">{{ $trait->title }}</h3>
                        <p class="trait-description">{{ $trait->description }}</p>
                    </div>
                    
                    <div class="d-flex align-items-center flex-shrink-0 gap-3">
                        @php
                            $questionCount = $trait->subTraits->sum(fn($subTrait) => $subTrait->questions->count());
                        @endphp
                        
                        <span class="question-badge">{{ $questionCount }} Questions</span>

                        {{-- The button that triggers the JS deletion logic --}}
                        <button class="btn btn-sm btn-outline-danger border-0 delete-trait-btn" data-id="{{ $trait->id }}" title="Delete Trait">
                            <i class="bi bi-trash"></i> 
                        </button>
                    </div>
                </div>
                
                <div class="trait-body">
                    <a href="{{ route('manage.edit', $trait->id) }}" class="edit-btn text-black">Edit Questions</a>
                </div>
            </div>
            @empty
            <div class="alert alert-info trait-card">
                <p class="mb-0">No assessment traits have been defined yet. Click 'Add Trait' to begin building your questionnaire.</p>
            </div>
            @endforelse
        </div>
    </div>
     @vite(['resources/js/manage.js'])
</div>


@include('components.confirmation')

@include('components.toast')
@endsection