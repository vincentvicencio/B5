@extends('layouts.app')

@section('content')

    <div id="interpretation" class="py-6 sm:py-12">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- Headers from original Blade structure --}}
            <h1>Scoring Interpretation Configuration</h1>
            <p>Configure scoring interpretations for the personality assessment</p>

            <div class="card shadow-sm bg-white p-4 p-md-5 my-4 border-0 response-card">
                <h2 class="mb-4">Sub-Trait Score Interpretation Ranges</h2>
                <div id="sub-trait-ranges">

                </div>

                <hr class="border border opacity-75">
                <div class="d-grid mt-1">
                    <button class="btn add-btn btn-lg fw-bold" data-bs-toggle="modal" data-bs-target="#scoreModal">
                        <i class="bi bi-plus-lg me-2"></i> Add New Score Range
                    </button>
                </div>
            </div>

            <div class="card shadow-sm bg-white p-4 p-md-5 my-4 border-0 response-card">
                <h2 class="mb-4">Big Five Trait Score Interpretation Ranges</h2>
                <div id="big-five-ranges">

                </div>
                <hr class="border border opacity-75">
                <div class="d-grid mt-1">
                    <button class="btn add-btn btn-lg fw-bold" data-bs-toggle="modal" data-bs-target="#scoreModal">
                        <i class="bi bi-plus-lg me-2"></i> Add New Score Range
                    </button>
                </div>
            </div>

            {{-- Keep your original Add/Edit Modal --}}
            <div class="modal fade" id="scoreModal" tabindex="-1" aria-labelledby="scoreModalLabel" aria-hidden="true">
                {{-- ... modal content ... --}}
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="scoreModalLabel">Configure Score Range</h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form>
                                <div class="mb-3">
                                    <label for="modalScoreRange" class="form-label">Score Range (e.g., 1-10)</label>
                                    <input type="text" class="form-control" id="modalScoreRange" placeholder="e.g., 1-10">
                                </div>
                                <div class="mb-3">
                                    <label for="modalTraitLevel" class="form-label">Trait Level</label>
                                    <input type="text" class="form-control" id="modalTraitLevel"
                                        placeholder="e.g., Low, Moderate">
                                </div>
                                <div class="mb-3">
                                    <label for="modalDescriptionText" class="form-label">Description</label>
                                    <textarea class="form-control" id="modalDescriptionText" rows="3"
                                        placeholder="Enter detailed description..."></textarea>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="button" class="btn save-btn">Save Changes</button>
                        </div>
                    </div>
                </div>
            </div>

     @vite(['resources/js/interpretation.js'])


            {{-- INCLUDE THE REUSABLE COMPONENTS --}}
            @include('components.confirmation')
            @include('components.toast')

        </div>

    </div>

@endsection