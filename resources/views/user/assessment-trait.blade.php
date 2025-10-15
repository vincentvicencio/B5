@extends('layouts.user.app')

@section('content')

<main>
    <div class="form-container py-4">
        <div class="container-logo justify-content-center text-center mb-4">
            <img src="{{ asset('images/logo.png') }}" alt="Magellan Solutions" class="banner-logo">
        </div>

        <div class="trait-header" style="border: 2px solid {{ $trait->trait_display_color }};
                     background: linear-gradient(to right, {{ $trait->trait_display_color }}, transparent) left no-repeat;  background-size: 8px 100%;">
            <h2>Now Assessing: {{ $trait->title }}</h2>
            <p>{{ $trait->description }}</p>
        </div>

        <form action="{{ route('assessment.trait.store', ['traitId' => $trait->id]) }}" method="POST" id="traitForm">
            @csrf

            @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            @endif

            @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong>Please fix the following errors:</strong>
                <ul class="mb-0 mt-2">
                    @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            @endif

            @foreach($trait->subTraits as $subTraitIndex => $subTrait)
            <div class="subtrait-section">
                <div class="subtrait-header" style="background-color: {{ $trait->trait_display_color }}; opacity: 0.1;">
                    <h3>{{ $subTrait->subtrait_name }}</h3>
                </div>

                @foreach($subTrait->questions as $questionIndex => $question)
                <div class="question-card" style="border-left-color: {{ $trait->trait_display_color }};">
                    <p class="question-text">{{ $question->question_text }}</p>
                    <p class="question-instruction">Choose the option that best reflects how much you relate to the statement.</p>

                    <div class="likert-scale">
                        @foreach($likertScales as $scale)
                        <div class="likert-option">
                            <label for="q{{ $question->id }}_{{ $scale->value }}">
                                <span class="likert-label">{{ $scale->label }}</span>
                                <input type="radio"
                                    name="responses[{{ $question->id }}]"
                                    id="q{{ $question->id }}_{{ $scale->value }}"
                                    value="{{ $scale->value }}"
                                    {{ isset($savedResponses[$question->id]) && $savedResponses[$question->id] == $scale->value ? 'checked' : '' }}
                                    required>
                            </label>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endforeach
            </div>
            @endforeach

            <div class="button-group">
                @if(!$isFirstTrait)
                <a href="{{ route('assessment.trait', $previousTraitId) }}" class="btn-back">
                    Previous
                </a>
                @else
                <a href="{{ route('personal-info') }}" class="btn-back">
                    Previous
                </a>
                @endif

                <button type="submit"
                    class="btn btn-continue"
                    id="nextBtn"
                    disabled>
                    @if($isLastTrait)
                    Submit Assessment
                    @else
                    Next
                    @endif
                </button>
            </div>
        </form>
    </div>
</main>

@vite(['resources/css/app.css', 'resources/js/app.js'])

@endsection