<div class="modal fade notification-wrapper" id="deleteConfirmationModal" data-bs-backdrop="static"
    data-bs-keyboard="false" tabindex="-1" aria-labelledby="deleteConfirmationModal-label" role="dialog"
    aria-hidden="true">
     <div class="modal-dialog modal-dialog-start modal-md">
        <div class="modal-content border-0 shadow">
            <div class="modal-header trait-modal-header text-white">
                <h5 class="modal-title text-white" id="delete-title">Confirm Deletion</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                    aria-label="Close"></button>
                </div>
            <div class="modal-body">
                <div class="notification">
                    <h5 class="card-title text-center mt-3" id="delete-message">
                        </h5>

                    <input type="hidden" name="delete_record_id" id="delete_record_id"
                        class="notification_record">
                    </div>
                <div class="modal-footer border-0">
                    {{-- CRITICAL FIX: Ensure the Cancel button has data-bs-dismiss="modal" --}}
                    <button type="button" class="btn btn-secondary px-4"
                        data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-danger px-4" id="btn_delete_ok">Yes,
                        Delete</button>
                    </div>
                </div>
            </div>
        </div>
</div>

{{-- Question Edit Modal (Included for reusability across create/edit pages) --}}
<div class="modal fade" id="editQuestionModal" tabindex="-1" aria-labelledby="editQuestionModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-start modal-md">
        <div class="modal-content border-0 shadow">
            <div class="modal-header trait-modal-header text-white">
                <h5 class="modal-title fw-bold" id="editQuestionModalLabel">Edit Assessment Question</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <div class="modal-body">
                {{-- Used by JS to track the question's index --}}
                <input type="hidden" id="editQuestionIndex"> 
                
                <div class="mb-3">
                    <label for="editQuestionSubTraitSelect" class="form-label fw-semibold">Move to Subsection</label>
                    <select class="form-select" id="editQuestionSubTraitSelect"></select>
                </div>
                <div class="mb-0">
                    <label for="editQuestionText" class="form-label fw-semibold">Question Text</label>
                    <textarea class="form-control" id="editQuestionText" rows="3" required></textarea>
                </div>
            </div>
            <div class="modal-footer border-0 pt-2">
                <button type="button" class="btn btn-outline-secondary p-2" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary btn-save p-2" id="confirmEditQuestionBtn"
                    onclick="confirmEditQuestion()">
                    <i class="bi bi-check-lg me-1"></i> Save Changes
                </button>
            </div>
        </div>
    </div>
</div>

{{-- Specific Question Deletion Confirmation Modal (Moved from edit.blade.php for reusability) --}}
<div class="modal fade" id="deleteQuestionConfirmationModal" tabindex="-1"
    aria-labelledby="deleteQuestionConfirmationModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-start modal-md">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title fw-bold text-white" id="deleteQuestionConfirmationModalLabel">Confirm Deletion</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="questionToDeleteIndex">
                <p class="mb-2 text-muted">Are you sure you want to delete this question?</p>
                <p class="text-dark fw-semibold" id="questionToDeleteTextPlaceholder"></p>
                <p class="mb-0 small text-danger">This action cannot be undone.</p>
            </div>
            <div class="modal-footer border-0 pt-2">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" onclick="confirmDeleteQuestion()">
                    <i class="bi bi-trash me-1"></i> Delete
                </button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="editSubTraitModal" tabindex="-1" aria-labelledby="editSubTraitModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-start modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-white" id="editSubTraitModalLabel">Edit Sub-section Name</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                {{-- Placeholder where old name is displayed --}}
                <p>Editing: <strong id="oldSubTraitNamePlaceholder"></strong></p>
                <div class="mb-3">
                    <label for="newSubTraitNameInput" class="form-label">New Name</label>
                    {{-- CRITICAL ID: Matches DOM.newSubTraitNameInput in JS --}}
                    <input type="text" class="form-control" id="newSubTraitNameInput" required>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                {{-- CRITICAL ID: Matches DOM.confirmEditSubTraitBtn in JS --}}
                <button type="button" class="btn btn-primary" id="confirmEditSubTraitBtn" onclick="saveEditedSubTrait()">
                    Save Changes
                </button>
            </div>
        </div>
    </div>
</div>