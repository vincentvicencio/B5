@extends('layouts.user.app')

@section('content')

<div class="completion-container py-4 d-flex justify-content-center"  id="assessment-complete">
    <div class="completion-wrapper">
        
        @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
            <strong>Error:</strong> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @endif

        @if(session('success'))
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

            @if(session('assessment_id'))
            <p class="text-muted small mt-3">
                <strong>Reference ID:</strong> {{ session('assessment_id') }}
            </p>
            @endif
        </div>
        @else
        <!-- No success message - show generic completion -->
        <div class="text-center">
            <div class="status-badge mx-auto">
                <i class="bi bi-check-circle-fill"></i>
                <span>Assessment Complete</span>
            </div>
        </div>

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
        @endif

        <!-- Back to Home Button -->
        <div class="text-end mt-4">
            <a href="{{ route('landing') }}" class="btn-home d-inline-flex align-items-center justify-content-center">
                <i class="bi bi-house-door-fill me-2"></i>
                Back to Home
            </a>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Log page load
    console.log('Assessment Complete Page Loaded', {
        hasSuccess: {{ session('success') ? 'true' : 'false' }},
        hasError: {{ session('error') ? 'true' : 'false' }},
        assessmentId: '{{ session('assessment_id') ?? 'none' }}'
    });
    
    // Prevent going back to assessment after completion
    if (window.history && window.history.pushState) {
        window.history.pushState(null, null, window.location.href);
        window.onpopstate = function() {
            window.history.pushState(null, null, window.location.href);
        };
    }
});
</script>

@endsection