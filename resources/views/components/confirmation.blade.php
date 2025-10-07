<div class="modal fade notification-wrapper" id="deleteConfirmationModal" data-bs-backdrop="static"
    data-bs-keyboard="false" tabindex="-1" aria-labelledby="deleteConfirmationModal-label" role="dialog"
    aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="delete-title">Confirm Deletion</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="notification">
                    <h5 class="card-title   mt-3" id="delete-message">
                    </h5>

                    <input type="hidden" name="delete_record_id" id="delete_record_id" class="notification_record">
                </div>
                <div class="modal-footer border-0 mt-4">
                    <button type="button" class="btn btn-secondary px-4 me-2" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-danger px-4" id="btn_delete_ok">Yes, Delete</button>
                </div>
            </div>
        </div>
    </div>
</div>