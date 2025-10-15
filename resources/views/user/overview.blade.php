@extends('layouts.user.app')

@section('content')

<main class="text-center flex-grow-1">
    <section class="container py-5 py-lg-7">
        <div class="row justify-content-center">
            <div class="col-lg-10 col-xl-8">
                
                <span class="top d-inline-block px-3 py-1 fw-medium rounded-pill mb-4">
                    <span class="bullet">●</span>
                    Intermediate Level
                </span>

                <h1 class="display-magellan fw-bolder mb-3 lh-sm">
                    <span class="text1">Big Five</span><br>
                    <span class="text2">Personality Assessment</span>
                </h1>

                <p class="subtitle fs-5 text-secondary mx-auto mb-5">
                    Complete our comprehensive assessment to understand your workplace personality and receive personalized insights for career development.
                </p>
                
                <div class="row mb-5 g-4">
                    <div class="col-12 col-md-4">
                        <div class="info-card">
                            <div class="icons">
                                <i class="icon-bi bi bi-clock"></i>
                            </div>
                            <p class="fw-bold mb-1">Duration</p>
                            <p class="small mb-0">10-15 minutes</p>
                        </div>
                    </div>

                    <div class="col-12 col-md-4">
                        <div class="info-card">
                            <div class="icons">
                                <i class="icon-bi bi bi-question-circle"></i>
                            </div>
                            <p class="fw-bold mb-1">Question</p>
                            <p class="small mb-0">10 question per traits</p>
                        </div>
                    </div>

                    <div class="col-12 col-md-4">
                        <div class="info-card">
                            <div class="icons">
                                <i class="icon-bi bi bi-graph-up"></i>
                            </div>
                            <p class="fw-bold mb-1">Traits</p>
                            <p class="small mb-0">5 dimensions</p>
                        </div>
                    </div>
                </div>
                
                <div class="overview-box">
                    <h3>Assessment Overview</h3>
                    <p>
                        This assessment will evaluate your personality across five key dimensions:
                    </p>
                    
                    <ul class="list-unstyled mb-0">
                        <li class="trait-list-item">
                            <span class="bullet">●</span>
                            <span>
                                <strong>Openness to Experience</strong> - Measures creativity, intellectual curiosity, and preference for novelty
                            </span>
                        </li>
                        <li class="trait-list-item">
                            <span class="bullet">●</span>
                            <span>
                                <strong>Conscientiousness</strong> - Measures organization, discipline, and goal-directed behavior
                            </span>
                        </li>
                        <li class="trait-list-item">
                            <span class="bullet">●</span>
                            <span>
                                <strong>Extraversion</strong> - Measures sociability, assertiveness, and energy level
                            </span>
                        </li>
                        <li class="trait-list-item">
                            <span class="bullet">●</span>
                            <span>
                                <strong>Agreeableness</strong> - Measures cooperation, trust, and empathy
                            </span>
                        </li>
                        <li class="trait-list-item">
                            <span class="bullet">●</span>
                            <span>
                                <strong>Neuroticism</strong> - Measures emotional resilience and stress management
                            </span>
                        </li>
                    </ul>
                </div>
                
                <div class="instructions-box">
                    <p>
                        <strong>Instructions:</strong> Please answer honestly and spontaneously. There are no right or wrong answers. Your responses will be kept confidential.
                    </p>
                </div>

                <a href="{{ route('personal-info') }}" class="btn btn-lg text-white assessment-button d-inline-flex align-items-center">
                    Start Your Assessment 
                    <i class="btn-bi bi bi-arrow-right ms-3"></i>
                </a>
            </div>
            @vite (['resources/sass/app.scss'])
        </div>
    </section>
</main>

@endsection