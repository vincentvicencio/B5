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
     * Sets a custom click handler for the 'Yes, Delete' button in the modal.
     * Use this for non-API deletion (e.g., deleting a question from a local array).
     * @param {function} handler - The function to execute on click.
     */
    setupButtonHandler(handler) {
        // Unbind any previous handler (to prevent running both API and local logic)
        $("#deleteConfirmationModal #btn_delete_ok").off("click");

        // Bind the new custom handler
        $("#deleteConfirmationModal #btn_delete_ok").on("click", function (event) {
            handler(event);
        });
    }

    /**
     * Sets up the click handler for the 'Yes, Delete' button to perform RESTful API deletion.
     * This is intended for the Index page where records are deleted from the database.
     * @param {string} baseUrl - The base API endpoint (e.g., '/api/manage').
     * @param {function} callback - Function to run on successful deletion (e.g., removing the element from DOM).
     */
    setupDeleteConfirmation(baseUrl, callback) {
        const self = this;
        
        // Use the generic setup method, wrapping the API logic
        this.setupButtonHandler(function () {
            const recordId = $(
                "#deleteConfirmationModal #delete_record_id"
            ).val(); 
            
            const deleteUrl = `${baseUrl}/${recordId}`;
            $.ajax({
                url: deleteUrl,
                type: "DELETE", // Use DELETE method for RESTful API
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
                },
                success: function (response) {
                    // FIX: Explicitly hide the Bootstrap modal using the proper instance method.
                    const modalElement = document.getElementById("deleteConfirmationModal");
                    if (modalElement) {
                        const deleteModalInstance = bootstrap.Modal.getInstance(modalElement);
                        if (deleteModalInstance) {
                            deleteModalInstance.hide();
                        }
                    }

                    // Show success toast
                    self.showToast(
                        response.message || "Item deleted successfully!"
                    );

                    // Execute the page-specific callback (e.g., remove the element from DOM)
                    callback(recordId);
                },
                error: function (xhr) {
                    self.showToast(
                        xhr.responseJSON.message ||
                            "Error deleting the item. Please try again.",
                        1 // Error type
                    );
                    console.error("Delete failed:", xhr.responseText);
                },
            });
        });
    }

    /**
     * Shows the delete confirmation modal with dynamic content.
     * This function only handles setting content and showing the modal.
     * The button handler must be set separately using setupButtonHandler or setupDeleteConfirmation.
     */
    showDeleteConfirmation(id, title, message) {
        // Set dynamic content
        $("#deleteConfirmationModal #delete-title").text(title);
        $("#deleteConfirmationModal #delete-message").html(message); // Use .html() to support strong tags and snippets
        $("#deleteConfirmationModal #delete_record_id").val(id); // Set the ID to the hidden input

        // Show the modal
        var deleteModal = new bootstrap.Modal(
            document.getElementById("deleteConfirmationModal")
        );
        deleteModal.show();
    }
    
    /**
     * @param msg = notification message
     * @param type = 0: Success, 1: Error, 2: Warning (New)
     */
    showToast(msg, type = 0) {
        const $toast = $(".toast");
        const $header = $toast.find(".toast-header");

        // 🚨 MODIFIED LOGIC HERE 🚨
        let headerClass = "bg-success";
        if (type === 1) {
            headerClass = "bg-danger";
        } else if (type === 2) { 
            headerClass = "bg-warning";
        }

        $header
            .removeClass("bg-success bg-danger bg-warning")
            .addClass(headerClass);
            
        // Set the message text in the toast body
        $toast.find(".toast-body strong").text(msg);

        // Bring to front
        $toast.css("z-index", 10000);

        // Initialize and show the toast
        var toast = new bootstrap.Toast($toast[0]); 

        toast.show();
    }

    showError(formid, index, value) {
        var elem_id = "#" + index;
        $(formid + " " + elem_id)
            .removeClass("is-valid")
            .addClass("is-invalid")
            .after(
                '<div class="invalid-feedback text-danger error-' +
                    index +
                    '">' +
                    value +
                    "</div>"
            );
    }
}

const CommonInstance = new Common();
export default CommonInstance;