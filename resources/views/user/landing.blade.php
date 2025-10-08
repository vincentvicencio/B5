<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Magellan Solutions - Trait Assessment</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
</head>
<body class="min-vh-100 d-flex flex-column">

    <header class="header-bg sticky-top z-10">
        <div class="container py-2">
            <div class="d-flex align-items-center" >
                <img src="{{ asset('images/magellan.png') }}" alt="Magellan Solutions Logo" class="img-fluid">
            </div>
        </div>
    </header>

    <main class="text-center flex-grow-1">
        <!-- Hero Section -->
        <section class="container py-5 py-lg-7">
            <div class="row justify-content-center">
                <div class="col-lg-10 col-xl-8">

                    <span class="top d-inline-block px-3 py-1 fw-medium rounded-pill mb-4">
                        Professional Personality Assessment Tool
                    </span>

                    <h1 class="display-magellan fw-bolder text-dark mb-3 lh-sm">
                        <span class="text1">Trait Assessment for</span>
                        <span class="text2"> Hiring Excellence</span>
                    </h1>

                    <p class="subtitle fs-5 text-secondary mx-auto mb-5" >
                        Evaluate candidates' personality traits with our scientifically-designed assessment. Get actionable insights for better hiring decisions.
                    </p>

                    <a href="{{ route('overview') }}" class="btn btn-lg border-0 text-white assessment-button d-inline-flex align-items-center px-5 py-3 rounded-3">
                    Start Your Assessment
                    <i class=" btn-bi bi bi-arrow-right ms-3"></i>
                    </a>

                    <p class="mt-4 small text-muted">
                        Takes approximately 15-20 minutes to complete
                    </p>
                </div>
            </div>
        </section>

        <section class="container pb-5 pb-lg-5">
            <div class="row row-cols-1 row-cols-sm-2 row-cols-lg-4 g-4 g-md-5 justify-content-center">
                
                @foreach($features as $feature)
                    <div class="col">
                        <div class="feature-card card h-100 border-0 shadow-sm rounded-4 p-4 text-center d-flex flex-column align-items-center">
                            
                            <!-- Icon Circle -->
                            <div class="icons p-3 rounded-3 mb-4 d-flex align-items-center justify-content-center" >
                                
                                @if($feature['title'] === 'Quick & Efficient')
                                    <!-- Clock Icon -->
                                    <i class="icon-bi bi bi-clock"></i>
                                @elseif($feature['title'] === 'Detailed Analytics')
                                    <!-- Bar Chart Icon -->
                                    <i class="icon-bi bi bi-bar-chart"></i>
                                @elseif($feature['title'] === 'Team Focused')
                                    <!-- People Icon -->
                                    <i class="icon-bi bi bi-people"></i>
                                @elseif($feature['title'] === 'Professional Grade')
                                    <!-- Shield Icon -->
                                    <i class="icon-bi bi bi-shield-check"></i>
                                @endif
                            </div>

                            <h3 class="fs-5 fw-bold text-dark mb-2">
                                {{ $feature['title'] }}
                            </h3>

                            <p class="small text-secondary mb-0">
                                {{ $feature['description'] }}
                            </p>
                        </div>
                    </div>
                @endforeach
            </div>
        </section>
    </main>

</body>
</html>
