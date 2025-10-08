@extends('layouts.user.app')

@section('content')

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
        <div class="success-card ">
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
        <div class="text-end">
            <a href="{{ route('landing') }}" class="btn-home d-inline-flex align-items-center justify-content-center">
                <i class="bi bi-house-door-fill me-2"></i>
                Back to Home
            </a>
        </div>
    </div>
</div>

@endsection