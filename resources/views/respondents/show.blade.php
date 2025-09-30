@extends('layouts.app')

@section('content')

    <div id="respondents-show" class="py-6 sm:py-12">

        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">

            <div class="d-flex w-100 justify-content-start align-items-center mb-4">
                <!-- Back Button -->
                <button onclick="history.back()" class="btn btn-link me-2 p-1 border-0 mb-3" aria-label="Go back">
                    <i class="bi bi-arrow-left fs-1"></i>
                </button>
                <div>
                    <!-- Main Title -->
                    <h1 class="ms-2 mb-1 fw-bold">Response Details</h1>
                    <p class="ms-2 mb-0">Detailed assessment results and analysis</p>
                </div>
            </div>

            <div class="card shadow-sm bg-white p-4 p-md-5 mb-4 border-0 response-card">


                <div class="d-flex align-items-center mb-4">
                    <i class="bi bi-person me-2"></i>
                    <h2 class="fw-bold mb-0">Personal Information</h2>
                </div>
                <div class="row ms-5">
                    <div class="col-6 col-md-5 mb-4">
                        <div class="info-label">Full Name:</div>
                        <div class="info-value">John Doe</div>
                    </div>

                    <div class="col-6 col-md-5 mb-4">
                        <div class="info-label">Email:</div>
                        <div class="info-value">john.doe@email.com</div>
                    </div>


                    <div class="col-6 col-md-5 mb-4">
                        <div class="info-label">Contact Number:</div>
                        <div class="info-value">+63 912 345 6789</div>
                    </div>

                    <div class="col-6 col-md-5 mb-4">
                        <div class="info-label">Recommended Position:</div>
                        <div class="info-value">Back Office</div>
                    </div>

                </div>
            </div>


            <div class="card shadow-sm bg-white p-4 p-md-5 mb-4 border-0 response-card">

                <h2 class="fw-bold mb-2">Big Five Trait Assessment Results</h2>
                <p class="mb-4">Comprehensive analysis of personality traits based on responses </p>

                <!-- Trait -->
                <div class="card bg-white p-4 p-md-5 mb-4 border-0 response-card">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <div id="trait" class="trait text-truncate me-2 flex-grow-1"> Openness</div>
                        <span id="trait-level" class="badge align-items-center fw-bold high-badge">High</span>
                    </div>
                    <p id="trait-description" class="text-secondary small mb-3">Creativity, curiosity, openness to new ideas
                    </p>

                    <div class="score-row mb-1">
                        <span id="trait-score" class="mb-1 me-3"> Score: 36/50 </span>
                        <p id="score-percentage" class="small text-secondary mb-0 ms-3 score-percentage">72.0%</p>
                    </div>
                    <div class="progress flex-grow-1">
                        <div class="progress-bar openness-progress-bar" role="progressbar" aria-valuenow="72"
                            aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                    <div id="trait-interpretation" class="trait-interpretation mt-2">Strongly characteristic of the trait
                    </div>

                    <h3 class="fw-semibold mt-4 mb-3"> Sub-Trait</h3>
                    <div class="row g-4">
                        <div class="col-md-6">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <span id="sub-trait" class="sub-trait">Creativity</span>
                                <span id="subtrait-score" class="subtrait-score small text-secondary">18/25</span>
                            </div>
                            <div class="progress mb-1">
                                <div id="sub-trait-progress-bar" class="progress-bar openness-progress-bar"
                                    role="progressbar" aria-valuenow="72" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                            <p id="subtrait-level" class="subtrait-level mb-0">High</p>
                            <p class="small text-secondary text-truncate">A core trait that defines and dominates an
                                individual's behavior.</p>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <span id="sub-trait" class="sub-trait">Intellectual Curiosity</span>
                                <span id="subtrait-score" class="subtrait-score text-secondary">16/25</span>
                            </div>
                            <div class="progress mb-1">
                                <div id="sub-trait-progress-bar" class="progress-bar openness-progress-bar"
                                    role="progressbar" aria-valuenow="64" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                            <p id="subtrait-level" class="subtrait-level small mb-0">High</p>
                            <p class="small text-truncate">A core trait that defines and dominates an individual's behavior.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Trait -->
                <div class="card bg-white p-4 p-md-5 mb-4 border-0 response-card">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <div id="trait" class="trait text-truncate me-2 flex-grow-1"> Conscientiousness </div>
                        <span id="trait-level" class="badge fw-bold align-items-center low-badge">Low</span>
                    </div>
                    <p id="trait-description" class="text-secondary small mb-3 trait-description">Cooperativeness, kindness,
                        trustworthiness</p>

                    <div class="score-row mb-1">
                        <span id="trait-score" class="mb-1 me-3"> Score: 15/50 </span>
                        <p id="score-percentage" class="small text-secondary mb-0 ms-3 score-percentage">20.0%</p>
                    </div>
                    <div class="progress flex-grow-1">
                        <div class="progress-bar conscientiousness-progress-bar" role="progressbar" aria-valuenow="20"
                            aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                    <div id="trait-interpretation" class="trait-interpretation mt-2"> Less characteristic of the trait
                    </div>

                    <h3 class="fw-semibold mt-4 mb-3"> Sub-Trait</h3>
                    <div class="row g-4">
                        <div class="col-md-6">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <span id="sub-trait" class="sub-trait">Diligence</span>
                                <span id="subtrait-score" class="subtrait-score small text-secondary">8/25</span>
                            </div>
                            <div class="progress mb-1">
                                <div id="sub-trait-progress-bar" class="progress-bar conscientiousness-progress-bar"
                                    role="progressbar" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                            <p id="subtrait-level" class="subtrait-level mb-0">Low</p>
                            <p class="small text-secondary text-truncate">The trait is not a prominent characteristic.</p>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <span id="sub-trait" class="sub-trait">Efficiency</span>
                                <span id="subtrait-score" class="subtrait-score text-secondary">8/25</span>
                            </div>
                            <div class="progress mb-1">
                                <div id="sub-trait-progress-bar" class="progress-bar conscientiousness-progress-bar"
                                    role="progressbar" aria-valuenow="64" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                            <p id="subtrait-level" class="subtrait-level small mb-0">Low</p>
                            <p class="small text-truncate">The trait is not a prominent characteristic.</p>
                        </div>
                    </div>
                </div>


                <!-- Trait -->
                <div class="card bg-white p-4 p-md-5 mb-4 border-0 response-card">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <div id="trait" class="trait text-truncate me-2 flex-grow-1"> Extraversion</div>
                        <span id="trait-level" class="badge fw-bold align-items-center low-badge">Low</span>
                    </div>
                    <p id="trait-description" class="text-secondary small mb-3 trait-description">Sociability,
                        assertiveness, enthusiasm</p>

                    <div class="score-row mb-1">
                        <span id="trait-score" class="mb-1 me-3"> Score: 15/50 </span>
                        <p id="score-percentage" class="small text-secondary mb-0 ms-3 score-percentage">20.0%</p>
                    </div>
                    <div class="progress flex-grow-1">
                        <div class="progress-bar extraversion-progress-bar" role="progressbar" aria-valuenow="20"
                            aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                    <div id="trait-interpretation" class="trait-interpretation mt-2"> Less characteristic of the trait
                    </div>

                    <h3 class="fw-semibold mt-4 mb-3"> Sub-Trait</h3>
                    <div class="row g-4">
                        <div class="col-md-6">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <span id="sub-trait" class="sub-trait">Assertiveness</span>
                                <span id="subtrait-score" class="subtrait-score small text-secondary">8/25</span>
                            </div>
                            <div class="progress mb-1">
                                <div id="sub-trait-progress-bar" class="progress-bar extraversion-progress-bar"
                                    role="progressbar" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                            <p id="subtrait-level" class="subtrait-level mb-0">Low</p>
                            <p class="small text-secondary text-truncate">The trait is not a prominent characteristic.</p>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <span id="sub-trait" class="sub-trait">Sociability</span>
                                <span id="subtrait-score" class="subtrait-score text-secondary">8/25</span>
                            </div>
                            <div class="progress mb-1">
                                <div id="sub-trait-progress-bar" class="progress-bar extraversion-progress-bar"
                                    role="progressbar" aria-valuenow="64" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                            <p id="subtrait-level" class="subtrait-level small mb-0">Low</p>
                            <p class="small text-truncate">The trait is not a prominent characteristic.</p>
                        </div>
                    </div>
                </div>


                <!-- Trait -->
                <div class="card bg-white p-4 p-md-5 mb-4 border-0 response-card">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <div id="trait" class="trait text-truncate me-2 flex-grow-1"> Agreeableness</div>
                        <span id="trait-level" class="badge align-items-center fw-bold high-badge">High</span>
                    </div>
                    <p id="trait-description" class="text-secondary small mb-3">Cooperativeness, kindness, trustworthiness
                    </p>

                    <div class="score-row mb-1">
                        <span id="trait-score" class="mb-1 me-3"> Score: 36/50 </span>
                        <p id="score-percentage" class="small text-secondary mb-0 ms-3 score-percentage">72.0%</p>
                    </div>
                    <div class="progress flex-grow-1">
                        <div class="progress-bar agreeableness-progress-bar" role="progressbar" aria-valuenow="72"
                            aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                    <div id="trait-interpretation" class="trait-interpretation mt-2">Strongly characteristic of the trait
                    </div>

                    <h3 class="fw-semibold mt-4 mb-3"> Sub-Trait</h3>
                    <div class="row g-4">
                        <div class="col-md-6">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <span id="sub-trait" class="sub-trait">Compassion</span>
                                <span id="subtrait-score" class="subtrait-score small text-secondary">18/25</span>
                            </div>
                            <div class="progress mb-1">
                                <div id="sub-trait-progress-bar" class="progress-bar agreeableness-progress-bar"
                                    role="progressbar" aria-valuenow="72" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                            <p id="subtrait-level" class="subtrait-level mb-0">High</p>
                            <p class="small text-secondary text-truncate">A core trait that defines and dominates an
                                individual's behavior.</p>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <span id="sub-trait" class="sub-trait">Politeness</span>
                                <span id="subtrait-score" class="subtrait-score text-secondary">16/25</span>
                            </div>
                            <div class="progress mb-1">
                                <div id="sub-trait-progress-bar" class="progress-bar agreeableness-progress-bar"
                                    role="progressbar" aria-valuenow="64" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                            <p id="subtrait-level" class="subtrait-level small mb-0">High </p>
                            <p class="small text-truncate">A balanced and adaptable expression of the trait.</p>
                        </div>
                    </div>
                </div>

                <!-- Trait -->
                <div class="card bg-white p-4 p-md-5 mb-4 border-0 response-card">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <div id="trait" class="trait text-truncate me-2 flex-grow-1"> Neuroticism</div>
                        <span id="trait-level" class="badge fw-bold align-items-center moderate-badge">Moderate</span>
                    </div>
                    <p id="trait-description" class="text-secondary small mb-3 trait-description">Emotional stability,
                        anxiety, mood stability</p>

                    <div class="score-row mb-1">
                        <span id="trait-score" class="mb-1 me-3"> Score: 25/50 </span>
                        <p id="score-percentage" class="small text-secondary mb-0 ms-3 score-percentage">50.0%</p>
                    </div>
                    <div class="progress flex-grow-1">
                        <div class="progress-bar neuroticism-progress-bar" role="progressbar" aria-valuenow="50"
                            aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                    <div id="trait-interpretation" class="trait-interpretation mt-2"> Moderate expression of the trait
                    </div>

                    <h3 class="fw-semibold mt-4 mb-3"> Sub-Trait</h3>
                    <div class="row g-4">
                        <div class="col-md-6">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <span id="sub-trait" class="sub-trait">Diligence</span>
                                <span id="subtrait-score" class="subtrait-score small text-secondary">15/25</span>
                            </div>
                            <div class="progress mb-1">
                                <div id="sub-trait-progress-bar" class="progress-bar neuroticism-progress-bar"
                                    role="progressbar" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                            <p id="subtrait-level" class="subtrait-level mb-0">Moderate</p>
                            <p class="small text-secondary text-truncate">A balanced and adaptable expression of the trait.
                            </p>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <span id="sub-trait" class="sub-trait">Efficiency</span>
                                <span id="subtrait-score" class="subtrait-score text-secondary">15/25</span>
                            </div>
                            <div class="progress mb-1">
                                <div id="sub-trait-progress-bar" class="progress-bar neuroticism-progress-bar"
                                    role="progressbar" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                            <p id="subtrait-level" class="subtrait-level small mb-0">Moderate</p>
                            <p class="small text-truncate">A balanced and adaptable expression of the trait.</p>
                        </div>
                    </div>
                </div>


            </div>
        </div>
@endsection