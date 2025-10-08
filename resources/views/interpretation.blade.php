@extends('layouts.app')

@section('content')

<div id="interpretation" class="py-4">
    <div class="container-fluid px-4">

        <div class="mb-4">
            <h1>Scoring Interpretation Configuration</h1>
            <p class="mb-0">Configure scoring interpretations for the personality assessment.</p>
        </div>

        {{-- Sub-Trait Score Interpretation Ranges --}}
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-body p-4">
                <div class="mb-4">
                    <h2 class="mb-1">Sub-Trait Score Interpretation Ranges</h2>
                </div>

                <div id="subTraitInterpretationContainer">
                    <div class="text-center py-5">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <p class="text-muted mt-3 mb-0">Loading interpretations...</p>
                    </div>
                </div>

                <hr class="my-3">
                <button type="button" class="btn btn-outline-primary w-100" onclick="showAddInterpretationModal('sub-trait')">
                    <i class="bi bi-plus-lg me-1"></i> Add New Interpretation
                </button>
            </div>
        </div>

        {{-- Big Five Trait Score Interpretation Ranges --}}
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-body p-4">
                <div class="mb-4">
                    <h2 class="mb-1">Trait Score Interpretation</h2>
                </div>

                <div id="traitInterpretationContainer">
                    <div class="text-center py-5">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <p class="text-muted mt-3 mb-0">Loading interpretations...</p>
                    </div>
                </div>

                <hr class="my-3">
                <button type="button" class="btn btn-outline-primary w-100" onclick="showAddInterpretationModal('trait')">
                    <i class="bi bi-plus-lg me-1"></i> Add New Interpretation
                </button>
            </div>
        </div>

    </div>
</div>

{{-- Interpretation Modal (Add/Edit) --}}
<div class="modal fade" id="interpretationModal" tabindex="-1" aria-labelledby="interpretationModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-start">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-white fw-medium" id="interpretationModalLabel">Add New Interpretation</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="interpretationForm" novalidate>
                    <input type="hidden" id="interpretationId">
                    <input type="hidden" id="interpretationTypeId">

                    <div class="mb-3">
                        <label for="traitLevel" class="form-label">Trait Level <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="traitLevel" placeholder="e.g., Low, Moderate, High" required maxlength="255">
                        <div class="invalid-feedback">Please provide a trait level name.</div>
                    </div>

                    <div class="mb-3">
                        <label for="interpretationText" class="form-label">Interpretation <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="interpretationText" rows="4" placeholder="Enter the interpretation text..." required></textarea>
                        <div class="invalid-feedback">Please provide the interpretation text.</div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                    <i class="bi bi-x-lg me-1"></i> Cancel
                </button>
                <button type="button" class="btn btn-primary" onclick="saveInterpretation()">
                    <i class="bi bi-check-lg me-1"></i> Save Interpretation
                </button>
            </div>
        </div>
    </div>
</div>

@include('components.confirmation')
@include('components.toast')

@vite(['resources/js/interpretation.js'])

@endsection