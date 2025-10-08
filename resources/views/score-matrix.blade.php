@extends('layouts.app')

@section('content')
    <div id="score-matrix-page" class="py-6 sm:py-12">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="header-content">
                <div class="row w-100 g-2">
                    <div class="col-12 col-sm-8 col-md-9 header-text">
                        <h1>Scoring Matrix Configuration</h1>
                        <p class="text-muted">Configure scoring matrices for the personality assessment</p>
                    </div>

                    {{-- Likert Rating Scale Section --}}
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-body p-4">
                            <div class="mb-3">
                                <h2 class="fw-bold mb-1">Likert Rating Scale</h2>
                                <p class="text-muted small mb-0">Define the response scale for assessment questions</p>
                            </div>

                            <div id="likertScalesContainer" class="mb-3">
                                <div class="text-center py-3">
                                    <div class="spinner-border spinner-border-sm text-primary" role="status">
                                        <span class="visually-hidden">Loading...</span>
                                    </div>
                                </div>
                            </div>

                            <hr class="my-3">
                            <button type="button" class="btn btn-outline-primary w-100" onclick="showAddLikertModal()">
                                <i class="bi bi-plus-lg me-1"></i> Add New Rating
                            </button>
                        </div>
                    </div>

                    {{-- Sub-Trait Scoring Matrix Section --}}
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-body p-4">
                            <div class="mb-3">
                                <h2 class="fw-bold mb-1">Sub-Trait Scoring Matrix</h2>
                                <p class="text-muted small mb-0">Configure score ranges and interpretations for sub-traits
                                </p>
                            </div>

                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th width="5%" class="text-center">#</th>
                                            <th width="18%">Sub-Trait Name</th>
                                            <th width="18%">Parent Trait</th>
                                            <th width="15%">Score Range</th>
                                            <th width="18%">Interpretation</th>
                                            <th width="18%">Updated Date</th>
                                            <th width="8%" class="text-center">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody id="subTraitMatrixTableBody">
                                        <tr>
                                            <td colspan="7" class="text-center text-muted py-3">
                                                <div class="spinner-border spinner-border-sm" role="status">
                                                    <span class="visually-hidden">Loading...</span>
                                                </div>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                            <div class="mt-3">
                                <button type="button" class="btn btn-outline-primary w-100"
                                    onclick="showAddSubTraitMatrixModal()">
                                    <i class="bi bi-plus-lg me-1"></i> Add New Sub-Trait
                                </button>
                            </div>
                        </div>
                    </div>

                    {{-- Big Five Trait Scoring Configuration Section --}}
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-body p-4">
                            <div class="mb-3">
                                <h2 class="fw-bold mb-1"> Trait Scoring Configuration</h2>
                                <p class="text-muted small mb-0">Configure scoring ranges for main personality traits</p>
                            </div>

                            <div id="traitMatrixContainer">
                                <div class="text-center py-3">
                                    <div class="spinner-border spinner-border-sm text-primary" role="status">
                                        <span class="visually-hidden">Loading...</span>
                                    </div>
                                </div>
                            </div>

                            <div class="mt-3">
                                <button type="button" class="btn btn-outline-primary w-100" onclick="showAddTraitModal()">
                                    <i class="bi bi-plus-lg me-1"></i> Add New Trait
                                </button>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            {{-- Likert Scale Modal --}}
            <div class="modal fade" id="likertScaleModal" tabindex="-1">
                <div class="modal-dialog modal-dialog-start">
                    <div class="modal-content">
                        <div class="modal-header border-0">
                            <h5 class="modal-title text-white fw-medium" id="likertScaleModalLabel">Add Rating</h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <input type="hidden" id="likertScaleId">

                            <div class="mb-3"> 
                                <label for="likertValue" class="form-label fw-semibold">Rating Value <span
                                        class="text-danger">*</span></label>
                                <input type="number" class="form-control" id="likertValue" placeholder="e.g., 1 "required>
                            </div>
                            <div class="mb-3">
                                <label for="likertLabel" class="form-label fw-semibold">Rating Label <span
                                        class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="likertLabel"
                                    placeholder="e.g., Strongly Disagree" required>
                            </div>
                        </div>
                        <div class="modal-footer border-0">
                            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal"> Cancel
                            </button>
                            <button type="button" class="btn save-btn px-4" onclick="saveLikertScale()"> Save
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Sub-Trait Matrix Modal --}}
            <div class="modal fade" id="subTraitMatrixModal" tabindex="-1">
                <div class="modal-dialog modal-dialog-start modal-lg">
                    <div class="modal-content">
                        <div class="modal-header border-0">
                            <h5 class="modal-title text-white fw-medium" id="subTraitMatrixModalLabel">Add Sub-Trait
                                Matrix</h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <input type="hidden" id="subTraitMatrixId">

                            <div class="mb-3">
                                <label for="subTraitSelect" class="form-label fw-semibold">Sub-Trait <span
                                        class="text-danger">*</span></label>
                                <select class="form-select" id="subTraitSelect" required>
                                    <option value="">Select Sub-Trait</option>
                                </select>
                            </div>

                            <div class="row g-3 mb-3">
                                <div class="col-md-6">
                                    <label for="subTraitMinScore" class="form-label fw-semibold">Min Score <span
                                            class="text-danger">*</span></label>
                                    <input type="number" class="form-control" id="subTraitMinScore" min="0"
                                        placeholder="e.g., 5" required>
                                </div>
                                <div class="col-md-6">
                                    <label for="subTraitMaxScore" class="form-label fw-semibold">Max Score <span
                                            class="text-danger">*</span></label>
                                    <input type="number" class="form-control" id="subTraitMaxScore"
                                        placeholder="e.g., 25" required>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="subTraitInterpretationSelect" class="form-label fw-semibold">Interpretation
                                    <span class="text-danger">*</span></label>
                                <select class="form-select" id="subTraitInterpretationSelect" required>
                                    <option value="">Select Interpretation</option>
                                </select>
                                <div class="form-text">
                                    <i class="bi bi-info-circle me-1"></i>
                                    Manage interpretations in the <a href="{{ route('interpretation') }}"
                                        target="_blank">Interpretation</a> section
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer border-0">
                            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel
                            </button>
                            <button type="button" class="btn save-btn px-4" onclick="saveSubTraitMatrix()">Save
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Trait Matrix Modal --}}
            <div class="modal fade" id="traitMatrixModal" tabindex="-1">
                <div class="modal-dialog modal-dialog-start modal-lg">
                    <div class="modal-content">
                        <div class="modal-header border-0">
                            <h5 class="modal-title text-white fw-medium" id="traitMatrixModalLabel">Add Trait
                                Configuration</h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <input type="hidden" id="traitMatrixId">

                            <div class="mb-3">
                                <label for="traitSelect" class="form-label fw-semibold">Trait <span
                                        class="text-danger">*</span></label>
                                <select class="form-select" id="traitSelect" required>
                                    <option value="">Select Trait</option>
                                </select>
                            </div>

                            <div class="row g-3 mb-3">
                                <div class="col-md-6">
                                    <label for="traitMinScore" class="form-label fw-semibold">Total Min Score <span
                                            class="text-danger">*</span></label>
                                    <input type="number" class="form-control" id="traitMinScore" min="0"
                                        placeholder="e.g., 10" required>
                                </div>
                                <div class="col-md-6">
                                    <label for="traitMaxScore" class="form-label fw-semibold">Total Max Score <span
                                            class="text-danger">*</span></label>
                                    <input type="number" class="form-control" id="traitMaxScore" placeholder="e.g., 50"
                                        required>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="traitInterpretationSelect" class="form-label fw-semibold">Interpretation <span
                                        class="text-danger">*</span></label>
                                <select class="form-select" id="traitInterpretationSelect" required>
                                    <option value="">Select Interpretation</option>
                                </select>
                                <div class="form-text">
                                    <i class="bi bi-info-circle me-1"></i>
                                    Manage interpretations in the <a href="{{ route('interpretation') }}"
                                        target="_blank">Interpretation</a> section
                                </div>
                            </div>

                            <div class="alert alert-light border d-flex align-items-start">
                                <i class="bi bi-info-circle-fill text-primary me-2 mt-1"></i>
                                <div class="small w-100">
                                    <strong>Sub-Trait Scores (Range):</strong>
                                    <div id="traitSubTraitsDisplay" class="mt-2">
                                        <span class="text-muted">Select a trait to view its sub-traits</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer border-0">
                            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal"> Cancel
                            </button>
                            <button type="button" class="btn save-btn px-4" onclick="saveTraitMatrix()"> Save
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            @include('components.toast')
            @include('components.confirmation')
            @vite(['resources/js/score-matrix.js'])
        @endsection
