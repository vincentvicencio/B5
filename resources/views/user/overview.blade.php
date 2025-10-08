@extends('layouts.user.app')

@section('content')
<style>
    .header-bg {
        min-height: 3rem;
        background-color: white;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }
    
    img {
        max-height: 70px;
    }

    .top {
        font-size: 1rem;
        color: #072F6D;
        background-color: #07306d3a;
    }

    .text1 {
        color: #1a1a1a;
    }

    .text2 {
        color: #072F6D;
    }

    .display-magellan {
        font-size: 2.5rem;
    }

    @media (min-width: 576px) {
        .display-magellan {
            font-size: 3.5rem;
        }
    }

    @media (min-width: 992px) {
        .display-magellan {
            font-size: 4rem;
        }
    }

    .subtitle {
        color: #6c757d;
        max-width: 850px;
    }

    /* Info cards styling */
    .info-card {
        background-color: #ffffff;
        outline: rgba(0, 0, 0, 0.062) 1px solid;
        box-shadow: 0 4px 15px rgba(0, 119, 255, 0.068) !important;
        border-radius: 1rem;
        padding: 2rem 1.5rem;
        height: 100%;
        text-align: center;
    }

    .icons {
        width: 3.5rem;
        height: 3.5rem;
        background-color: #07306d3a;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 0.75rem;
        margin-bottom: 0.75rem;
    }

    .icon-bi {
        font-size: 1.75rem;
        color: #072F6D;
    }

    .info-card .fw-bold {
        font-size: 1rem;
        color: #1a1a1a;
        margin-bottom: 0.25rem;
    }

    .info-card .small {
        color: #6c757d;
        font-size: 0.875rem;
    }

    /* Assessment Overview box */
    .overview-box {
        background-color: #f8f9fa;
        border-radius: 1rem;
        padding: 2.5rem;
        text-align: left;
        margin-bottom: 2rem;
        outline: rgba(0, 0, 0, 0.062) 1px solid;
        box-shadow: 0 4px 15px rgba(0, 119, 255, 0.068);
    }

    .overview-box h3 {
        color: #1a1a1a;
        font-size: 1.25rem;
        font-weight: 700;
        margin-bottom: 1rem;
    }

    .overview-box > p {
        color: #1a1a1a;
        font-size: 0.95rem;
        margin-bottom: 1.5rem;
    }

    .trait-list-item {
        margin-bottom: 1rem;
        font-size: 0.9rem;
        display: flex;
        align-items: flex-start;
    }

    .trait-list-item .bullet {
        color: #072F6D;
        font-size: 1.5rem;
        line-height: 1;
        margin-right: 0.75rem;
        margin-top: -0.25rem;
    }

    .trait-list-item strong {
        color: #072F6D;
        font-weight: 600;
    }

    .trait-list-item span {
        color: #1a1a1a;
    }

    /* Instructions box */
    .instructions-box {
        background-color: #fff8e1;
        border-left: 4px solid #ffc107;
        padding: 1.25rem 1.5rem;
        border-radius: 0.5rem;
        text-align: left;
        margin-bottom: 2.5rem;
    }

    .instructions-box p {
        color: #1a1a1a;
        font-size: 0.9rem;
        margin-bottom: 0;
    }

    /* Button styling */
    .assessment-button {
        background-color: #072F6D !important;
        border: 0;
        transition: all 0.3s ease;
        box-shadow: 0 4px 15px rgba(27, 85, 151, 0.4);
        font-size: 1rem;
        padding: 0.875rem 2.5rem;
        border-radius: 0.75rem;
        font-weight: 600;
    }

    .assessment-button:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(27, 85, 151, 0.6);
    }

    .btn-bi {
        font-size: 1.25rem;
    }

    /* Container adjustments */
    .assessment-container {
        max-width: 900px;
        margin: 0 auto;
    }
</style>

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
                                <strong>Openness to Experience</strong> – Measures creativity, intellectual curiosity, and preference for novelty
                            </span>
                        </li>
                        <li class="trait-list-item">
                            <span class="bullet">●</span>
                            <span>
                                <strong>Conscientiousness</strong> – Measures organization, discipline, and goal-directed behavior
                            </span>
                        </li>
                        <li class="trait-list-item">
                            <span class="bullet">●</span>
                            <span>
                                <strong>Extraversion</strong> – Measures sociability, assertiveness, and energy level
                            </span>
                        </li>
                        <li class="trait-list-item">
                            <span class="bullet">●</span>
                            <span>
                                <strong>Agreeableness</strong> – Measures cooperation, trust, and empathy
                            </span>
                        </li>
                        <li class="trait-list-item">
                            <span class="bullet">●</span>
                            <span>
                                <strong>Neuroticism</strong> – Measures emotional resilience and stress management
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
        </div>
    </section>
</main>

@endsection