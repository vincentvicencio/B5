@extends('layouts.user.app')

@section('content')
<style>
    .container-logo {
        background: linear-gradient(90deg, #072F6D 0%, rgba(27, 85, 151, 1) 100%);
        padding: 2.5rem;
        border-radius: 0.5rem;
    }

    .container-logo img {
        max-height: 200px;
    }

    .banner-logo {
        max-width: 350px;
        height: auto;
    }

    .form-container {
        max-width: 900px;
        margin: 0 auto;
        margin-top: 30px;
        padding: 0 2rem 3rem;
        background-color: #ffffff;
        box-shadow: 0 4px 15px rgba(0, 119, 255, 0.068) !important;
        border-radius: 1rem;
    }

    /* Trait Header - White background with border */
    .trait-header {
        background-color: #ffffff;
        padding: 2rem;
        border-radius: 0.5rem;
        margin-bottom: 1.5rem;
        border-left: 8px solid transparent;
        border: 2px solid {{ $trait->trait_display_color }};
        background: linear-gradient(to right, {{ $trait->trait_display_color }}, transparent) left no-repeat;
        background-size: 8px 100%;
        display: flex;
        flex-direction: column;

    }

    .trait-header h2 {
        color: #1a1a1a;
        font-size: 1.3rem;
        font-weight: 700;
        margin: 0 0 0.25rem 0;
    }

    .trait-header p {
        color: #6c757d;
        font-size: 0.85rem;
        margin: 0;
    }

    /* Subtrait Header - Colored background */
    .subtrait-header {
        padding: 1rem 1.5rem;
        border-radius: 0.5rem;
        margin-bottom: 0.5rem;
        background-color: color-mix(in srgb, {{ $trait->trait_display_color }} 90%, white);
    }

    .subtrait-header h3 {
        color: #ffffff;
        font-size: 1.1rem;
        text-shadow: #0000003a 1px 1px 2px;
        margin: 0;
    }

    /* Question Card - White background */
    .question-card {
        background-color: #ffffff;
        border: 1px solid #dee2e6;
        border-left: 4px solid;
        border-radius: 0.5rem;
        padding: 1.5rem;
        margin-bottom: 2rem;
    }

    .question-text {
        color: #1a1a1a;
        font-size: 0.95rem;
        font-weight: 500;
        margin-bottom: 0.5rem;
        line-height: 1.5;
    }

    .question-instruction {
        color: #6c757d;
        font-size: 0.8rem;
        margin-bottom: 1rem;
    }

    /* Likert Scale */
    .likert-scale {
        display: flex;
        justify-content: space-between;
        gap: 1rem;
        align-items: flex-start;
    }

    .likert-option {
        flex: 1;
        display: flex;
        flex-direction: column;
        align-items: center;
        text-align: center;
    }

    .likert-label {
        font-size: 0.75rem;
        color: #6c757d;
        margin-bottom: 0.5rem;
        font-weight: 500;
        display: block;
        line-height: 1.3;
        min-height: 2.5rem;
    }

    .likert-option input[type="radio"] {
        -webkit-appearance: none;
        -moz-appearance: none;
        appearance: none;
        width: 22px;
        height: 22px;
        border: 2px solid #d1d5db;
        border-radius: 50%;
        outline: none;
        cursor: pointer;
        transition: all 0.2s ease;
        margin: 0;
    }

    .likert-option input[type="radio"]:hover {
        border-color: #9ca3af;
        transform: scale(1.1);
    }

    .likert-option input[type="radio"]:checked {
        border-width: 7px;
    }

    /* Subtrait Spacing */
    .subtrait-section {
        margin-bottom: 1.5rem;
    }

    /* Button Group */
    .button-group {
    display: flex;
    justify-content: space-between; /* pushes buttons to opposite sides */
    align-items: center;
    gap: 1rem;
    margin-top: 2.5rem;
    padding-top: 1.5rem;
}

.btn-back {
    flex: 0 0 auto;
    background-color: #ffffff;
    border: 1px solid #dee2e6;
    color: #495057;
    padding: 0.875rem 1.5rem;
    border-radius: 0.5rem;
    font-weight: 600;
    font-size: 1rem;
    transition: all 0.3s ease;
    text-align: center;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
}

.btn-continue {
    flex: 0 0 auto; /* don’t stretch */
    background-color: #072F6D;
    border: 2px solid #072F6D;
    color: #ffffff;
    padding: 0.875rem 1.5rem;
    border-radius: 0.5rem;
    font-weight: 600;
    font-size: 1rem;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
}


    .btn-continue:hover {
        background-color: #072F6D;
        color: #ffffff;
    }

    /* Progress Text */
    .progress-text {
        text-align: center;
        margin-top: 1rem;
        font-size: 0.85rem;
        color: #6c757d;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .container-logo img {
            max-height: 90px;
        }

        .likert-scale {
            gap: 0.5rem;
        }

        .likert-label {
            font-size: 0.65rem;
            min-height: 2rem;
        }

        .likert-option input[type="radio"] {
            width: 18px;
            height: 18px;
        }

        .button-group {
            flex-direction: column;
        }
    }

    @media (max-width: 576px) {
        .form-container {
            padding: 0 1rem 2rem;
        }

        .likert-scale {
            gap: 0.25rem;
        }

        .likert-label {
            font-size: 0.6rem;
        }
    }
</style>

<main>
    <div class="form-container py-4">
        <!-- Logo Container -->
        <div class="container-logo justify-content-center text-center mb-4">
            <img src="{{ asset('images/logo.png') }}" alt="Magellan Solutions" class="banner-logo">
        </div>

        <!-- Trait Header -->
        <div class="trait-header" >
            <h2>Now Assessing: {{ $trait->title }}</h2>
            <p>{{ $trait->description }}</p>
        </div>

        <!-- Assessment Form -->
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
                <!-- Subtrait Header with Dynamic Color -->
                <div class="subtrait-header">
                    <h3>{{ $subTrait->subtrait_name }}</h3>
                </div>

                <!-- Questions for this Subtrait -->
                @foreach($subTrait->questions as $questionIndex => $question)
                <div class="question-card" style="border-left-color: {{ $trait->trait_display_color }};">
                    <p class="question-text">{{ $question->question_text }}</p>
                    <p class="question-instruction">Choose the option that best reflects how much you relate to the statement.</p>

                    <!-- Likert Scale -->
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

            <!-- Navigation Buttons -->
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
        </form>
    </div>
</main>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('traitForm');
    const nextBtn = document.getElementById('nextBtn');
    
    const allRadios = document.querySelectorAll('input[type="radio"]');
    const likertScaleCount = {{ $likertScales->count() }};
    const totalQuestions = allRadios.length / likertScaleCount;
    
    // Apply trait color to checked radio buttons
    allRadios.forEach(radio => {
        radio.addEventListener('change', function() {
            // Remove color from all radios with same name
            const sameName = document.querySelectorAll(`input[name="${this.name}"]`);
            sameName.forEach(r => {
                r.style.borderColor = '#d1d5db';
            });
            
            // Apply color to checked radio
            if (this.checked) {
                this.style.borderColor = '{{ $trait->trait_display_color }}';
            }
        });
        
        // Set initial color if already checked
        if (radio.checked) {
            radio.style.borderColor = '{{ $trait->trait_display_color }}';
        }
    });
    
    function updateProgress() {
        const answeredQuestions = new Set();
        
        document.querySelectorAll('input[type="radio"]:checked').forEach(radio => {
            answeredQuestions.add(radio.getAttribute('name'));
        });
        
        const answeredCount = answeredQuestions.size;
        nextBtn.disabled = answeredCount < totalQuestions;
    }
    
    allRadios.forEach(radio => {
        radio.addEventListener('change', updateProgress);
    });
    
    updateProgress();
    
    form.addEventListener('submit', function(e) {
        const answeredQuestions = new Set();
        document.querySelectorAll('input[type="radio"]:checked').forEach(radio => {
            answeredQuestions.add(radio.getAttribute('name'));
        });
        
        if (answeredQuestions.size < totalQuestions) {
            e.preventDefault();
            alert('Please answer all questions before continuing.');
            return false;
        }
        
        nextBtn.disabled = true;
        nextBtn.innerHTML = '<i class="bi bi-hourglass-split"></i> Processing...';
    });
});
</script>
@endsection