@extends('layouts.user.app')

@section('content')
<style>

    /* Completion Container */
    .completion-container {
        min-height: calc(100vh - 100px);
        display: flex;
        align-items: center;
        justify-content: center;
        background-color: #f7f7f7;
        padding: 2rem;
    }

    .completion-wrapper {
        max-width: 900px;
        width: 100%;
    }

    /* Status Badge */
    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        background-color: #d1ecf1;
        border: 2px solid #0c5460;
        border-radius: 2rem;
        padding: 0.5rem 1.5rem;
        margin-bottom: 2rem;
    }

    .status-badge i, .status-badge span {
        color: #0c5460;
        font-weight: 600;
        font-size: 0.95rem;
    }
    
    .status-badge i {
        font-size: 1.25rem;
    }

    /* Success Card */
    .success-card {
        background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%);
        border: 2px solid #28a745;
        border-radius: 1rem;
        padding: 3rem;
        text-align: center;
        margin-bottom: 2rem;
        box-shadow: 0 4px 15px rgba(40, 167, 69, 0.15);
    }

    .success-icon {
        width: 120px;
        height: 120px;
        background-color: #28a745;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 2rem;
        box-shadow: 0 4px 20px rgba(40, 167, 69, 0.3);
    }

    .success-icon i {
        font-size: 4rem;
        color: #ffffff;
    }

    .success-title {
        font-size: 2rem;
        font-weight: 700;
        color: #155724;
        margin-bottom: 1rem;
        line-height: 1.3;
    }

    .success-message {
        font-size: 1.1rem;
        color: #155724;
        line-height: 1.6;
        max-width: 600px;
        margin: 0 auto;
    }

    /* Home Button */
    .btn-home {
        background-color: #072F6D;
        border: 2px solid #072F6D;
        color: #ffffff;
        padding: 1rem 2.5rem;
        border-radius: 0.75rem;
        font-weight: 600;
        font-size: 1rem;
        text-decoration: none;
        transition: all 0.3s ease;
        box-shadow: 0 4px 15px rgba(7, 47, 109, 0.2);
    }

    .btn-home:hover {
        background-color: #05234d;
        border-color: #05234d;
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(7, 47, 109, 0.3);
        color: #ffffff;
    }

    .btn-home i {
        font-size: 1.2rem;
    }

    /* Responsive Adjustments */
    @media (max-width: 768px) {
        .completion-container {
            padding: 1rem;
        }

        .success-card {
            padding: 2rem 1.5rem;
        }

        .success-title {
            font-size: 1.5rem;
        }

        .success-message {
            font-size: 1rem;
        }

        .success-icon {
            width: 100px;
            height: 100px;
        }

        .success-icon i {
            font-size: 3rem;
        }

        .btn-home {
            padding: 0.875rem 2rem;
            font-size: 0.95rem;
        }
    }
</style>

<div class="completion-container">
    <div class="completion-wrapper">
        <!-- Status Badge -->
        <div class="text-center">
            <div class="status-badge mx-auto">
                <i class="bi bi-check-circle-fill"></i>
                <span>Assessment Complete</span>
            </div>
        </div>

        <!-- Success Card -->
        <div class="success-card">
            <div class="success-icon">
                <i class="bi bi-check-lg"></i>
            </div>

            <h2 class="success-title">
                Thank you for completing the assessment.
            </h2>

            <p class="success-message">
                Your results will be processed by our team and emailed to you within 3 to 5 business days.
            </p>
        </div>

        <!-- Back to Home Button -->
        <div class="text-center">
            <a href="{{ route('landing') }}" class="btn-home d-inline-flex align-items-center justify-content-center">
                <i class="bi bi-house-door-fill me-2"></i>
                Back to Home
            </a>
        </div>
    </div>
</div>

@endsection