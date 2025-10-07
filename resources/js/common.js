// resources/js/common.js

class Common {

    processData(url, method, data) {
        return $.ajax({
            url: window.location.origin + url,
            type: method,
            async: false,
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            data: data,
            success: function (data) {
                return data;
            },
        }).responseJSON;
    }

    /**
     * Sets up the click handler for the 'Yes, Delete' button in the modal.
     * Must be called once per page load to activate the delete logic.
     * @param {string} baseUrl - The base API endpoint (e.g., '/api/score-interpretation').
     * @param {function} callback - Function to run on successful deletion (e.g., removing the element from DOM).
     */
    setupDeleteConfirmation(baseUrl, callback) {
        const self = this;
        // Target the specific button ID in the delete modal component
        $("#deleteConfirmationModal #btn_delete_ok").off('click').on('click', function () {
            const recordId = $("#deleteConfirmationModal #delete_record_id").val();
            
            // Construct the full URL for the DELETE request
            const deleteUrl = `${baseUrl}/${recordId}`; 
            
            $.ajax({
                url: deleteUrl,
                type: "DELETE", // Use DELETE method for RESTful API
                headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                success: function (response) {
                    // FIX: Use jQuery/Bootstrap method to ensure the modal is hidden.
                    $('#deleteConfirmationModal').modal('hide');
                    
                    // Show success toast
                    self.showToast(response.message || 'Item deleted successfully!');
                    
                    // Execute the page-specific callback (e.g., remove from DOM)
                    if (callback) {
                         callback(recordId); 
                    }

                    // Optional: reload the page after a short delay
                    setTimeout(() => { location.reload() }, 2000); 
                },
                error: function(xhr) {
                    // FIX: Also hide the modal on error
                    $('#deleteConfirmationModal').modal('hide');
                    self.showToast(xhr.responseJSON?.message || 'Error deleting item.', 1); // Error toast
                }
            });
        });
    }

    /**
     * Shows the delete confirmation modal and sets the data needed for deletion.
     * @param {number|string} id - The ID of the record to be deleted.
     * @param {string} title - The modal title.
     * @param {string} message - The confirmation message.
     */
    showDeleteConfirmation(id, title = 'Confirm Deletion', message = 'Are you sure you want to delete this record?') {
        $("#deleteConfirmationModal #delete-title").text(title);
        $("#deleteConfirmationModal #delete-message").text(message);
        $("#deleteConfirmationModal #delete_record_id").val(id); // Set the ID to the hidden input

        var deleteModal = new bootstrap.Modal(document.getElementById('deleteConfirmationModal'));
        deleteModal.show();
    }
    
    /**
     *
     * @param msg = notification message
     * @param err = 1=Error, 0/null = Success
     */
    showToast(msg, err = 0) {
        const $toast = $('.toast');
        const $header = $toast.find('.toast-header');
        const isError = err > 0;
        
        // Apply color classes to the header only (bg-success or bg-danger)
        $header.removeClass('bg-success bg-danger').addClass(isError ? 'bg-danger' : 'bg-success');
        
        // Set the message text in the toast body
        $toast.find('.toast-body strong').text(msg);
        $toast.css('z-index', 10000); // Bring to front
        
        // Use Bootstrap's Toast API
        var toast = new bootstrap.Toast($toast[0], {
            delay: 3000
        });

        toast.show();
        
        // Ensure toast hides after delay
        setTimeout(() => {
             toast.hide(); 
        }, 3500); 
    }


    showError(formid, index, value) {
        var elem_id = '#' + index;
        $(formid + ' ' + elem_id).addClass('error-input');
        $(formid + ' .error-' + index).removeClass('d-none');
        $(formid + ' .error-' + index).html('<i class="bi bi-exclamation-circle-fill"></i> ' + value);
    }
}

export default new Common;
