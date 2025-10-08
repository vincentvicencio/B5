@extends('layouts.app')

@section('content')
    {{-- Back Button Header --}}
    <div id="back-icon" class="mb-4 mx-4">
        <a href="{{ route('manage.index') }}" class="text-decoration-none d-flex align-items-center text-dark">
            <i class="bi bi-arrow-left me-2 fs-3"></i>
            <div>
                <h1 class="mt-3 fs-2 fw-bold back-title">Edit Trait: {{ $trait->title }}</h1>
                <p class="mb-0 text-muted small">Modify the assessment section and its questions</p>
            </div>
        </a>
    </div>

    <div id="manage-edit" class="container-fluid px-4">

        <div id="message" class="mb-4"></div>

        {{-- Trait Information Card --}}
        <div class="trait-card mb-4 shadow-sm">
            <div class="p-4">
                <h5 class="section-header mb-4">
                    <i class="bi bi-list-ul me-2"></i> Trait Information
                </h5>
                <form id="traitForm">
                    @csrf
                    @method('PUT')
                    <div class="mb-3">
                        <label for="traitTitle" class="form-label fw-semibold">Trait Title <span
                                class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="traitTitle" name="traitTitle"
                            value="{{ old('traitTitle', $trait->title) }}" placeholder="Enter your section title" required>
                    </div>
                    <div class="mb-3">
                        <label for="traitDescription" class="form-label fw-semibold">Description <span
                                class="text-danger">*</span></label>
                        <textarea class="form-control" id="traitDescription" name="traitDescription" rows="3"
                            placeholder="Describe what this section measures..." required>{{ old('traitDescription', $trait->description) }}</textarea>
                    </div>
                    <div class="mb-0">
                        <label for="traitColor" class="form-label fw-semibold d-block">Display Color (Hex Code)</label>
                        <input type="color" class="form-control form-control-color w-100" id="traitColor"
                            name="traitColor" value="{{ old('traitColor', $trait->trait_display_color) }}"
                            title="Choose your color" required>
                    </div>

                    {{-- CRITICAL FIX: HIDDEN CONTAINERS MOVED HERE FOR SUBMISSION --}}
                    <div id="subTraitsContainer" class="d-none">
                        {{-- JS will populate this with hidden inputs like: <input type="hidden" name="subTraits[]" value="..."> --}}
                    </div>
                    <div id="questionsInputContainer" class="d-none">
                        {{-- JS will dynamically populate this with hidden inputs for submission --}}
                    </div>
                </form>
            </div>
        </div>

        {{-- Sub-Trait and Question Management Card --}}
        <div class="trait-card mb-4 shadow-sm">
            <div class="p-4">
                <h5 class="section-header mb-4">
                    <i class="bi bi-question-circle me-2"></i> Assessment Questions
                </h5>

                {{-- Sub-Trait Management Area --}}
                <div class="mb-4">
                    <label class="form-label fw-semibold mb-3">Manage Subsections</label>
                    <div class="d-flex flex-column flex-md-row gap-2 mb-3">
                        <input type="text" class="form-control flex-grow-1" id="newSubTraitInput"
                            placeholder="Enter new subsection name" onkeypress="handleSubTraitInput(event)">
                        <button type="button" class="btn btn-add-subtrait px-4 text-nowrap" id="addSubTraitBtn"
                            onclick="addSubTraitFromInput()">
                            <i class="bi bi-plus-lg me-1"></i> Add Subsection
                        </button>
                    </div>

                    {{-- Dynamic Sub-Trait Display (Chips) --}}
                    <div id="subTraitsDisplay" class="d-flex flex-wrap gap-2">
                        {{-- JS will insert chips here --}}
                    </div>

                </div>

                <hr>

                {{-- Question Creation Area --}}
                <div class="question-form-area mt-4">
                    <h6 class="fw-bold mb-3 text-secondary">Add New Question</h6>
                    <div class="mb-3">
                        <label for="questionSubTraitSelect" class="form-label fw-semibold">Select subsection for question
                            <span class="text-danger">*</span></label>
                        <select class="form-select" id="questionSubTraitSelect" required>
                            <option value="" disabled selected>Select sub-section</option>
                            {{-- Options populated by JS on page load/sub-trait change --}}
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="questionText" class="form-label fw-semibold">Question Text <span
                                class="text-danger">*</span></label>
                        <textarea class="form-control" id="questionText" rows="3" placeholder="Enter assessment question..." required></textarea>
                        <small class="text-muted d-block mt-2">Respondents will answer on a 5-point Likert scale (Strongly
                            Disagree to Strongly Agree).</small>
                    </div>

                    <button type="button" class="btn btn-add-question w-100 py-3" id="addQuestionToListBtn"
                        onclick="addQuestionToList()">
                        <i class="bi bi-plus-lg me-2"></i> Add Question
                    </button>
                </div>
            </div>
        </div>

        {{-- Added Questions List --}}
        <div class="trait-card mb-4 shadow-sm">
            <div class="p-4">
                <h5 class="fw-bold mb-3 text-header-color">Added Questions (<span
                        id="questionCount">{{ $trait->subTraits->flatMap(fn($st) => $st->questions)->count() }}</span>)</h5>
                <div id="addedQuestionsContainer" class="list-group list-group-flush">
                    @php
                        // Flatten questions from subTraits for easier iteration and initial JS state
                        $questions = $trait->subTraits
                            ->flatMap(
                                fn($st) => $st->questions->map(
                                    fn($q) => [
                                        'subTrait' => $st->subtrait_name,
                                        'text' => $q->question_text,
                                    ],
                                ),
                            )
                            ->values();
                    @endphp

                    @foreach ($questions as $index => $question)
                        <div class="list-group-item d-flex justify-content-between align-items-center question-item"
                            data-index="{{ $index }}">
                            <div class="flex-grow-1 me-3">
                                <small
                                    class="text-primary fw-semibold question-subtrait-display">{{ $question['subTrait'] }}</small>
                                <p class="mb-0 question-text-display">{{ $question['text'] }}</p>
                            </div>
                            <div class="btn-group" role="group">
                                {{-- IMPORTANT: Using data-index and relying on JS event delegation --}}
                                <button type="button" class="btn btn-sm btn-outline-secondary edit-question-btn"
                                    data-index="{{ $index }}" title="Edit Question">
                                    <i class="bi bi-pencil"></i>
                                </button>
                                {{-- IMPORTANT: Using data-index and relying on JS event delegation --}}
                                <button type="button" class="btn btn-sm btn-outline-danger remove-question-btn"
                                    data-index="{{ $index }}" title="Delete Question">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- REMOVED: #questionsInputContainer was moved inside #traitForm above. --}}
            </div>
        </div>

        {{-- Action Buttons --}}
        <div class="d-flex justify-content-end gap-3 pb-5">
            <button type="button" class="btn btn-cancel px-5 py-2"
                onclick="window.location.href='{{ route('manage.index') }}'">Cancel</button>
            <button type="button" class="btn btn-save px-5 py-2" onclick="showSaveModal()">Update Section</button>
        </div>

    </div>

    {{-- Save Confirmation Modal (Update) --}}
    <div class="modal fade" id="saveConfirmationModal" tabindex="-1" aria-labelledby="saveConfirmationModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-start modal-md">
            <div class="modal-content border-0 shadow">
                <div class="modal-header text-white">
                    <h5 class="modal-title text-white fw-md" id="saveConfirmationModalLabel">Confirm Update</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p class="text-dark mb-2">
                        You are about to update the Trait: <strong id="saveTraitNamePlaceholder"
                            class="text-dark">{{ $trait->title }}</strong>.
                    </p>
                    <p class="mb-0 text-dark small">
                        Do you wish to proceed with saving these changes to the assessment section?
                    </p>
                </div>
                <div class="modal-footer border-0 pt-2">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Review</button>
                    <button type="button" class="btn save-btn" id="confirmSaveBtn"
                        onclick="confirmSave()"></i> Update Trait
                    </button>
                </div>
            </div>
        </div>
    </div>

    @include('components.confirmation')

    {{-- FIX: Include the generic toast notification partial (toast.blade.php) --}}
    @include('components.toast')
    
    <script>
    
        const INITIAL_SUB_TRAITS = '{!! json_encode($subTraits) !!}';
        const INITIAL_QUESTIONS = '{!! json_encode($questions) !!}';

        // Global Constants
        const TRAIT_ID = '{{ $trait->id }}';

        // Routes for AJAX
        const MANAGE_UPDATE_ROUTE = '{{ route('manage.update', $trait->id) }}';
        const MANAGE_INDEX_ROUTE = '{{ route('manage.index') }}';
    </script>
    @vite(['resources/js/manage.js']) 
@endsection