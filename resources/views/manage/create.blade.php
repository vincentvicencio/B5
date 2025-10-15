@extends('layouts.app')

@section('content')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    {{-- Back Button Header --}}
   <div id="back-icon" class="mb-4 mx-4">
    <a href="{{ route('manage.index') }}" class="text-decoration-none d-inline-flex align-items-center">
        <i class="bi bi-arrow-left me-2 fs-4 mb-2"></i>
        <div>
            <h1 class="mt-3 fs-2 fw-bold back-title">Add New Trait</h1>
            <p class="mb-0 text-muted small">Create a new assessment section with questions</p>
        </div>
    </a>
</div>

    {{-- Main Form Content --}}
    <div id="manage-create" class="container-fluid px-4">

        {{-- Message Area --}}
        <div id="message" class="mb-4"></div>

        {{-- Trait Information Card (The main form block) --}}
        <div class="trait-card mb-4">
            <div class="p-4">
                <h5 class="section-header mb-4">
                    <i class="bi bi-list-ul me-2"></i> Trait Information
                </h5>
                <form id="traitForm">
                    @csrf
                    <div class="mb-3">
                        <label for="traitTitle" class="form-label fw-semibold">Trait Title <span
                                class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="traitTitle" name="traitTitle"
                            placeholder="Enter your section title" required>
                    </div>
                    <div class="mb-3">
                        <label for="traitDescription" class="form-label fw-semibold">Description <span
                                class="text-danger">*</span></label>
                        <textarea class="form-control" id="traitDescription" name="traitDescription" rows="3"
                            placeholder="Describe what this section measures..." required></textarea>
                    </div>
                    <div class="mb-0">
                        <label for="traitColor" class="form-label fw-semibold d-block">Display Color (Hex Code)</label>
                        <input type="color" class="form-control form-control-color w-100" id="traitColor"
                            name="traitColor" value="#172c43" title="Choose your color" required>
                    </div>

                    {{-- CRITICAL FIX: HIDDEN CONTAINERS FOR DYNAMIC DATA --}}
                    {{-- manage.js requires these IDs to save the sub-traits and questions --}}
                    <div id="subTraitsContainer" style="display: none;">
                    </div>
                    <div id="questionsInputContainer" style="display: none;">
                    </div>

                </form>
            </div>
        </div>

        {{-- Assessment Questions Card --}}
        <div class="trait-card mb-4">
            <div class="p-4">
                <h5 class="section-header mb-4">
                    <i class="bi bi-question-circle me-2"></i> Assessment Questions
                </h5>

                <div class="mb-4">
                    <label class="form-label fw-semibold mb-3">Add Sub-Trait</label>
                    <div class="d-flex gap-2">
                        <input type="text" class="form-control" id="newSubTraitInput"
                            placeholder="Enter your sub-trait name" onkeypress="handleSubTraitInput(event)">
                        <button type="button" class="btn btn-add-subtrait px-4 text-nowrap" id="addSubTraitBtn"
                            onclick="addSubTraitFromInput()">
                            <i class="bi bi-plus-lg me-1"></i> Add Subsection
                        </button>
                    </div>
                </div>

                <div id="subTraitsDisplay" class="d-flex flex-wrap gap-2 mb-4">
                    {{-- This is the visible area for sub-trait tags --}}
                </div>

                {{-- Question Creation Area --}}
                <div class="question-form-area mt-4">
                    <div class="mb-3">
                        <label for="questionSubTraitSelect" class="form-label fw-semibold">Select sub-trait for
                            question</label>
                        <select class="form-select" id="questionSubTraitSelect" required>
                            <option value="" disabled selected>Select sub-section</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="questionText" class="form-label fw-semibold">Question Text</label>
                        <textarea class="form-control" id="questionText" rows="3" placeholder="Enter assessment question..." required></textarea>
                        <small class="text-muted d-block mt-2">Respondents will answer based on the provided rating scale,
                            where 1 represents Strongly Disagree, 2 is Disagree, 3 is Neutral, 4 is Agree, and 5 is Strongly
                            Agree.</small>
                    </div>

                    <button type="button" class="btn btn-add-question w-100 py-3" id="addQuestionToListBtn"
                        onclick="addQuestionToList()">
                        <i class="bi bi-plus-lg me-2"></i> Add Question
                    </button>
                </div>
            </div>
        </div>

        {{-- Added Questions List --}}
        <div class="trait-card mb-4">
            <div class="p-4">
                <h5 class="fw-bold mb-3 text-header-color">Added Questions (<span id="questionCount">0</span>)</h5>
                <div id="addedQuestionsContainer" class="list-group list-group-flush">
                </div>
            </div>
        </div>

        {{-- Action Buttons --}}
        <div class="d-flex justify-content-end gap-3 pb-5">
            <button type="button" class="btn btn-cancel px-5 py-2"
                onclick="window.location.href='{{ route('manage.index') }}'">Cancel</button>
            <button type="button" class="btn btn-save px-5 py-2" onclick="showSaveModal()">Save Section</button>
        </div>

    </div>

    {{-- Edit Question Modal --}}
    <div id="editQuestionModal" class="modal fade" tabindex="-1" aria-labelledby="editQuestionModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-start modal-lg">
            <div class="modal-content border-0 shadow">

                <div class="modal-header">>
                    <h5 class="modal-title text-white fw-md" id="editQuestionModalLabel"> Edit Question
                    </h5>
                    <button type="button" class="btn-close btn-close-white mb-2" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>

                <div class="modal-body pt-2">

                    {{-- Hidden input to store the index of the question being edited (optional, but good for clarity) --}}
                    <input type="hidden" id="editQuestionIndex">

                    <div class="mb-3">
                        <label for="editQuestionSubTraitSelect"
                            class="form-label fw-semibold">Sub-trait/Subsection</label>
                        {{-- CRITICAL ID: Used to set the sub-trait dropdown value --}}
                        <select class="form-select" id="editQuestionSubTraitSelect" required>
                            {{-- Options are populated dynamically by manage.js in updateQuestionSubTraitOptions() --}}
                        </select>
                    </div>

                    <div class="mb-0">
                        <label for="editQuestionText" class="form-label fw-semibold">Question Text</label>
                        {{-- CRITICAL ID: Used to get and set the question text --}}
                        <textarea class="form-control" id="editQuestionText" rows="4" required autofocus></textarea>
                    </div>
                </div>

                <div class="modal-footer border-0 pt-2">
                    <button type="button" class="btn btn-secondary btn-md px-3 py-2"
                        data-bs-dismiss="modal">Cancel</button>
                    {{-- CRITICAL ID: The JS event listener for confirmEditQuestion() is attached to this button --}}
                    <button type="button" class="btn save-btn px-4 py-2 btn-md" id="confirmEditQuestionBtn"> Save Changes
                    </button>
                </div>

            </div>
        </div>
    </div>


    {{-- Save Confirmation Modal --}}
    <div id="saveConfirmationModal" class="modal fade" tabindex="-1" aria-labelledby="saveConfirmationModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-start-centered modal-md">
            <div class="modal-content border-0 shadow">

                <div class="modal-header">
                    <h5 class="modal-title text-white fw-md" id="saveConfirmationModalLabel"> Confirm Save
                    </h5>
                    <button type="button" class="btn-close btn-close-white mb-2" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>

                <div class="modal-body pt-2">
                    <p class="text-dark mb-2">
                        You are about to save the new Trait: <strong id="saveTraitNamePlaceholder"
                            class="text-dark"></strong>.
                    </p>
                    <p class="mb-0 text-dark small">
                        Do you wish to proceed with saving this assessment section?
                    </p>
                </div>

                <div class="modal-footer border-0 pt-2">
                    <button type="button" class="btn btn-outline-secondary px-4" data-bs-dismiss="modal">Review</button>
                    <button type="button" class="btn save-btn px-4" onclick="confirmSave()"> Save Trait
                    </button>
                </div>

            </div>
        </div>
    </div>

    {{-- Sub-Trait Edit Modal --}}
    <div id="editSubTraitModal" class="modal fade" tabindex="-1" aria-labelledby="editSubTraitModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-start modal-md">
            <div class="modal-content border-0 shadow">

                <div class="modal-header">
                    <h5 class="modal-title text-white fw-md" id="editSubTraitModalLabel">Edit Subsection Name 
                    </h5>
                    <button type="button" class="btn-close btn-close-white mb-2" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>

                <div class="modal-body pt-2">
                    <p class="text-muted small mb-3">
                        Editing: <strong id="oldSubTraitNamePlaceholder" class="text-dark"></strong>
                    </p>
                    <div class="mb-0">
                        <label for="newSubTraitNameInput" class="form-label fw-semibold">New Subsection Name</label>
                        <input type="text" class="form-control" id="newSubTraitNameInput" required autofocus>
                    </div>
                </div>

                <div class="modal-footer border-0 pt-2">
                    <button type="button" class="btn btn-outline-secondary btn-md"
                        data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn save-btn" id="confirmEditSubTraitBtn"> Save Changes
                    </button>
                </div>

            </div>
        </div>
    </div>

    {{-- Final Required Includes --}}
    @include('components.toast')
    @include('components.confirmation')

    {{-- Pass routes to the global scope for the bundled JavaScript --}}
    <script>
        const MANAGE_STORE_ROUTE = '{{ route('manage.store') }}';
        const MANAGE_INDEX_ROUTE = '{{ route('manage.index') }}';
    </script>
    @vite(['resources/js/manage.js'])
@endsection
