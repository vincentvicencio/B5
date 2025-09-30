<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Magellan Solutions - Trait Assessment</title>
    <!-- Load Bootstrap 5 CSS for styling and responsiveness -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" xintegrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <!-- Load Inter Font -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100..900&display=swap" rel="stylesheet">
    
    <style>
        /* Define custom styles and variables */
        :root {
            --magellan-blue: #1b5597;
        }
        .magellan-blue {
            color: var(--magellan-blue) !important;
        }
        .magellan-bg-blue {
            background-color: var(--magellan-blue) !important;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: #f7f9fc; /* Light background matching the image */
        }
        .header-bg {
            background-color: white;
            box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        }
        
        /* Button hover/shadow effects */
        .assessment-button {
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(27, 85, 151, 0.4);
        }
        .assessment-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(27, 85, 151, 0.6);
        }
        .feature-card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .feature-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        }
        /* Custom responsive font size for main title */
        .display-magellan {
            font-size: 2.5rem; /* Base size */
        }
        @media (min-width: 576px) {
            .display-magellan {
                font-size: 3.5rem; /* Equivalent to md:text-5xl */
            }
        }
        @media (min-width: 992px) {
            .display-magellan {
                font-size: 4rem; /* Equivalent to lg:text-6xl */
            }
        }
    </style>
</head>
<body class="min-vh-100 d-flex flex-column">

    <!-- Header / Navigation Bar -->
    <header class="header-bg sticky-top z-10">
        <div class="container py-3">
            <div class="d-flex align-items-center" style="min-height: 3rem;">
                <div class="d-flex align-items-center">
                    <!-- Logo Mimic (using inline SVG for the graphic) -->
                    <div style="height: 2rem; width: 2rem;" class="me-3">
                        <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <circle cx="12" cy="12" r="10" class="fill-current text-primary opacity-50"/>
                            <path d="M12 2a10 10 0 00-4 18c0-3.5 1.5-6 4-6s4 2.5 4 6a10 10 0 004-18z" class="fill-current magellan-blue"/>
                        </svg>
                    </div>
                    <div class="d-flex flex-column lh-1">
                        <span class="fs-5 fw-semibold magellan-blue">magellan solutions</span>
                        <span class="small text-secondary mt-0.5">See the future your way</span>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content Area -->
    <main class="text-center flex-grow-1">
        <!-- Hero Section -->
        <section class="container py-5 py-lg-7">
            <div class="row justify-content-center">
                <div class="col-lg-10 col-xl-8">
                    <!-- Badge -->
                    <span class="d-inline-block px-3 py-1 fw-medium text-secondary-emphasis bg-light rounded-pill mb-4">
                        Professional Personality Assessment Tool
                    </span>
        
                    <!-- Main Title (Responsive Text Sizes) -->
                    <h1 class="display-magellan fw-bolder text-dark mb-3 lh-sm">
                        <span class="d-block">Trait Assessment</span>
                        <span class="magellan-blue">for Hiring Excellence</span>
                    </h1>
        
                    <!-- Subtitle/Description -->
                    <p class="fs-5 text-secondary mx-auto mb-5" style="max-width: 600px;">
                        Evaluate candidates' personality traits with our scientifically-designed assessment. Get actionable insights for better hiring decisions.
                    </p>
        
                    <!-- CTA Button -->
                    <a href="{{ route('assessment.start') }}" class="btn btn-lg border-0 text-white magellan-bg-blue assessment-button d-inline-flex align-items-center px-5 py-3 rounded-3">
                        Start Your Assessment
                        <!-- Arrow icon -->
                        <svg class="ms-3" style="width: 1.25rem; height: 1.25rem;" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                        </svg>
                    </a>
        
                    <!-- Info Text -->
                    <p class="mt-4 small text-muted">
                        Takes approximately 15-20 minutes to complete
                    </p>
                </div>
            </div>
        </section>

        <!-- Feature Cards Section -->
        <section class="container pb-5 pb-lg-5">
            <!-- Responsive Grid: 1 column on extra small, 2 columns on tablet, 4 columns on desktop -->
            <div class="row row-cols-1 row-cols-sm-2 row-cols-lg-4 g-4 g-md-5 justify-content-center">
                
                @foreach($features as $feature)
                    <div class="col">
                        <div class="feature-card card h-100 border-0 shadow-sm rounded-4 p-4 text-center d-flex flex-column align-items-center">
                            
                            <!-- Icon Circle -->
                            <div class="p-3 rounded-3 bg-light mb-4 d-flex align-items-center justify-content-center" style="width: 3.5rem; height: 3.5rem;">
                                <!-- SVG Icon (changes based on title) -->
                                <svg class="magellan-blue" style="width: 1.75rem; height: 1.75rem;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                    @if($feature['title'] === 'Quick & Efficient')
                                        <!-- Clock Icon -->
                                        <path d="M12 22a10 10 0 100-20 10 10 0 000 20zM12 6v6l4 2"/>
                                    @elseif($feature['title'] === 'Detailed Analytics')
                                        <!-- Chart Icon -->
                                        <path d="M3 3v18h18M18 17V9m-4 8V5m-4 12v-5"/>
                                    @elseif($feature['title'] === 'Team Focused')
                                        <!-- User/Group Icon -->
                                        <path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2M16 3.5a3.5 3.5 0 11-7 0 3.5 3.5 0 017 0zM17 11a5 5 0 010 10h-2"/>
                                    @elseif($feature['title'] === 'Professional Grade')
                                        <!-- Shield Icon -->
                                        <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10zM9 12l2 2 4-4"/>
                                    @endif
                                </svg>
                            </div>

                            <!-- Feature Title -->
                            <h3 class="fs-5 fw-bold text-dark mb-2">
                                {{ $feature['title'] }}
                            </h3>

                            <!-- Feature Description -->
                            <p class="small text-secondary mb-0">
                                {{ $feature['description'] }}
                            </p>
                        </div>
                    </div>
                @endforeach
            </div>
        </section>
    </main>

    <!-- Footer -->
    <footer class="text-center py-4 small text-muted bg-light border-top">
        &copy; {{ date('Y') }} Magellan Solutions. All rights reserved.
    </footer>
    
    <!-- Bootstrap JS Bundle (optional, but good practice) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" xintegrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

</body>
</html>
