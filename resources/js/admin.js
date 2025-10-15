import './common';

(function() {
    'use strict';

    /**
     * Notification Functions
     */

    function showNotification(message, type = 'success') {
        console.log('showNotification called:', message, type);
        
        // Try Bootstrap toast first
        const toastElement = document.querySelector('.toast');
        console.log('Toast element found:', toastElement ? 'Yes' : 'No');
        
        if (toastElement) {
            const toastHeader = toastElement.querySelector('.toast-header');
            const toastBody = toastElement.querySelector('.toast-body strong');
            
            console.log('Toast header:', toastHeader ? 'Found' : 'Not found');
            console.log('Toast body:', toastBody ? 'Found' : 'Not found');
            
            if (toastHeader && toastBody) {
                // Set colors based on type
                if (type === 'success') {
                    toastElement.querySelector('.toast-header').className = 'toast-header text-white bg-success';
                } else if (type === 'danger') {
                    toastElement.querySelector('.toast-header').className = 'toast-header text-white bg-danger';
                } else {
                    toastElement.querySelector('.toast-header').className = 'toast-header text-white bg-info';
                }
                
                // Set message
                toastBody.textContent = message;
                
                // Show toast using Bootstrap
                if (typeof bootstrap !== 'undefined' && bootstrap.Toast) {
                    const toast = new bootstrap.Toast(toastElement, {
                        autohide: true,
                        delay: 5000
                    });
                    toast.show();
                    console.log('Toast show() called');
                    return;
                }
            }
        }
        
        // Fallback to custom notification
        console.log('Using custom notification fallback');
        showCustomNotification(message, type);
    }

    function showCustomNotification(message, type = 'success') {
        // Remove any existing notifications first
        const existingNotifications = document.querySelectorAll('.custom-toast-notification');
        existingNotifications.forEach(notif => notif.remove());

        const notificationDiv = document.createElement('div');
        notificationDiv.className = 'custom-toast-notification';
        
        const iconColor = type === 'success' ? '#198754' : '#dc3545';
        const textColor = type === 'success' ? '#0f5132' : '#842029';
        
        notificationDiv.innerHTML = `
            <div class="custom-alert-box ${type}">
                <div class="custom-alert-content">
                    <i class="bi bi-${type === 'success' ? 'check-circle-fill' : 'exclamation-triangle-fill'}" 
                        style="font-size: 20px; color: ${iconColor}; flex-shrink: 0;"></i>
                    <span style="font-weight: 500; font-size: 15px; color: ${textColor};">${message}</span>
                </div>
                <button type="button" class="custom-close-btn" onclick="this.closest('.custom-toast-notification').remove()">
                    <i class="bi bi-x" style="font-size: 22px; color: ${textColor};"></i>
                </button>
            </div>
        `;
        
        document.body.appendChild(notificationDiv);
        
        // Trigger animation
        setTimeout(() => {
            notificationDiv.classList.add('show');
        }, 10);
        
        // Auto-hide after 5 seconds
        setTimeout(() => {
            notificationDiv.classList.remove('show');
            setTimeout(() => {
                if (notificationDiv.parentElement) {
                    notificationDiv.remove();
                }
            }, 300);
        }, 5000);
    }
    
    // -- Form Handling Functions ---

    function clearValidationErrors(form) {
        form.querySelectorAll('.is-invalid').forEach(input => {
            input.classList.remove('is-invalid');
        });
        form.querySelectorAll('.invalid-feedback').forEach(error => {
            error.remove();
        });
    }

    function displayValidationErrors(form, errors) {
        Object.keys(errors).forEach(key => {
            const input = form.querySelector(`[name="${key}"]`);
            if (input) {
                input.classList.add('is-invalid');
                
                // Remove existing error
                const existingError = input.parentElement.querySelector('.invalid-feedback');
                if (existingError) {
                    existingError.remove();
                }
                
                const errorDiv = document.createElement('div');
                errorDiv.className = 'invalid-feedback d-block';
                errorDiv.textContent = errors[key][0];
                input.parentElement.appendChild(errorDiv);
            }
        });
    }

    function updateTableRow(admin) {
        // Try multiple selectors to find the row
        let row = null;
        
        // Method 1: Find by edit button data-id
        row = document.querySelector(`a.edit-btnn[data-id="${admin.id}"]`)?.closest('tr');
        
        // Method 2: Find by delete form action
        if (!row) {
            row = document.querySelector(`form[action*="/admin/${admin.id}"]`)?.closest('tr');
        }
        
        // Method 3: Find by any element with data-admin-id attribute
        if (!row) {
            row = document.querySelector(`tr[data-admin-id="${admin.id}"]`);
        }
        
        if (row) {
            const cells = row.querySelectorAll('td');
            // Assuming the cells are in the order: checkbox, username, employee_code, first_name, last_name, email, updated_by, updated_at, actions
            if (cells.length >= 8) {
                cells[1].textContent = admin.username;
                cells[2].textContent = admin.employee_code || '-';
                cells[3].textContent = admin.first_name;
                cells[4].textContent = admin.last_name;
                cells[5].textContent = admin.email;
                cells[6].textContent = admin.updated_by || 'SuperAdmin';
                cells[7].textContent = new Date(admin.updated_at).toLocaleDateString('en-US', {
                    month: '2-digit',
                    day: '2-digit',
                    year: 'numeric'
                });
            }
            console.log('Row updated successfully');
        } else {
            console.log('Row not found for update');
        }
    }

    function handleFormSubmit(form, successMessage, isUpdate = false) {
        clearValidationErrors(form);
        
        const formData = new FormData(form);
        const actionUrl = form.action;
        
        // Disable submit button
        const submitBtn = form.querySelector('button[type="submit"]');
        const originalBtnHtml = submitBtn ? (isUpdate ? 'Update Admin' : 'Create Admin') : '';

        if (submitBtn) {
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Saving...';
        }
        
        fetch(actionUrl, {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
            }
        })
        .then(response => {
            if (!response.ok && response.status === 422) {
                return response.json().then(data => {
                    throw { validation: true, errors: data.errors };
                });
            }
            if (!response.ok) {
                 return response.json().then(data => {
                    throw new Error(data.message || 'Server error.');
                 });
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                // Close offcanvas
                const offcanvasElement = form.closest('.offcanvas');
                if (offcanvasElement) {
                    const offcanvas = bootstrap.Offcanvas.getInstance(offcanvasElement);
                    if (offcanvas) {
                        offcanvas.hide();
                    }
                }
                
                showNotification(successMessage, 'success');
                
                if (isUpdate && data.admin) {
                    // Update the table row without reloading
                    updateTableRow(data.admin);
                } else {
                    // For create, reload after a brief delay
                    setTimeout(() => {
                        window.location.reload();
                    }, 1000);
                }
            } else {
                showNotification(data.message || 'An error occurred', 'danger');
            }
        })
        .catch(error => {
            if (error.validation) {
                displayValidationErrors(form, error.errors);
            } else {
                console.error('Error:', error);
                showNotification(error.message || 'An error occurred while processing your request', 'danger');
            }
        })
        .finally(() => {
            if (submitBtn) {
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalBtnHtml;
            }
        });
    }

    // --- Delete Functions ---

    function showDeleteConfirmation(adminId, adminUsername) {
        const modal = document.getElementById('deleteConfirmationModal');
        const deleteMessage = document.getElementById('delete-message');
        const deleteRecordId = document.getElementById('delete_record_id');
        
        if (modal && deleteMessage && deleteRecordId) {
            deleteMessage.textContent = `Are you sure you want to delete admin user "${adminUsername}"? This action cannot be undone.`;
            deleteRecordId.value = adminId;
            
            if (typeof bootstrap !== 'undefined' && bootstrap.Modal) {
                 const bsModal = new bootstrap.Modal(modal);
                 bsModal.show();
            }
        }
    }

    function refreshTableData() {
        console.log('Refreshing table data...');
        
        // Get current URL with all parameters (search, page, etc.)
        const url = new URL(window.location.href);
        
        fetch(url.toString(), {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'text/html'
            }
        })
        .then(response => response.text())
        .then(html => {
            const parser = new DOMParser();
            const doc = parser.parseFromString(html, 'text/html');
            
            // Update table body
            const newTableBody = doc.querySelector('#adminTableBody');
            const currentTableBody = document.getElementById('adminTableBody');
            
            if (newTableBody && currentTableBody) {
                currentTableBody.innerHTML = newTableBody.innerHTML;
                console.log('Table body refreshed');
            }
            
            // Update pagination
            const newPagination = doc.querySelector('.pagination-container');
            const currentPagination = document.querySelector('.pagination-container');
            const tableContainer = document.querySelector('.table-responsive');

            if (newPagination && currentPagination) {
                currentPagination.innerHTML = newPagination.innerHTML;
                console.log('Pagination refreshed');
            } else if (newPagination && tableContainer) {
                // Add pagination if it doesn't exist
                const paginationDiv = document.createElement('div');
                paginationDiv.className = 'pagination-container p-3 border-top d-flex justify-content-center justify-content-md-end';
                paginationDiv.innerHTML = newPagination.innerHTML;
                tableContainer.insertAdjacentElement('afterend', paginationDiv);
                console.log('Pagination added');
            } else if (currentPagination && !newPagination) {
                // Remove pagination if no longer needed
                currentPagination.remove();
                console.log('Pagination removed');
            }
            
            // Re-initialize event listeners for the new content
            initializeTableActions();
            
            console.log('Table refresh complete');
        })
        .catch(error => {
            console.error('Error refreshing table:', error);
            // Fallback to full page reload if fetch fails
            window.location.reload();
        });
    }

    function handleDelete(adminId) {
        const modal = document.getElementById('deleteConfirmationModal');
        const bsModal = modal && typeof bootstrap !== 'undefined' && bootstrap.Modal ? 
                        bootstrap.Modal.getInstance(modal) : null;
        
        console.log('Attempting to delete admin ID:', adminId);
        
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
        
        const formData = new FormData();
        formData.append('_method', 'DELETE');
        
        fetch(`/admin/${adminId}`, {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            }
        })
        .then(response => {
            console.log('Response status:', response.status);
            return response.json();
        })
        .then(data => {
            console.log('Response data:', data);
            
            if (bsModal) {
                bsModal.hide();
            }
            
            if (data.success) {
                showNotification(data.message || 'Admin user deleted successfully.', 'success');
                
                // Refresh the entire table to get updated data from server
                setTimeout(() => {
                    refreshTableData();
                }, 300);
                
            } else {
                showNotification(data.message || 'Failed to delete admin user.', 'danger');
            }
        })
        .catch(error => {
            if (bsModal) {
                bsModal.hide();
            }
            console.error('Delete error:', error);
            showNotification('An error occurred while deleting the admin user.', 'danger');
        });
    }
    
    // --- Table Interaction Re-Initialization ---

    function initializeTableActions() {
        // Re-initialize edit buttons
        document.querySelectorAll('.edit-btnn').forEach(btn => {
            // Remove existing listener to prevent duplicates
            btn.removeEventListener('click', handleEditClick);
            btn.addEventListener('click', handleEditClick);
        });

        // Re-initialize delete forms
        document.querySelectorAll('.delete-form').forEach(form => {
            // Remove existing listener to prevent duplicates
            form.removeEventListener('submit', handleDeleteSubmit);
            form.addEventListener('submit', handleDeleteSubmit);
        });
    }

    function handleEditClick(e) {
        e.preventDefault();
        const adminId = this.dataset.id;
        fetchAdminAndOpenEditOffcanvas(adminId);
    }

    function handleDeleteSubmit(e) {
        e.preventDefault();
        const actionUrl = this.action;
        const adminId = actionUrl.split('/').pop();
        const row = this.closest('tr');
        const adminUsername = row ? row.querySelectorAll('td')[1]?.textContent.trim() : 'this user';
        showDeleteConfirmation(adminId, adminUsername);
    }

    function fetchAdminAndOpenEditOffcanvas(adminId, oldData = {}) {
        const editForm = document.getElementById('editAdminForm');
        const offcanvasElement = document.getElementById('editAdminOffcanvas');

        if (!editForm || !offcanvasElement) return;

        fetch(`/admin/${adminId}/edit`)
            .then(r => r.json())
            .then(admin => {
                document.getElementById('edit_admin_id').value = admin.id;
                document.getElementById('edit_username').value = oldData.username || admin.username;
                document.getElementById('edit_employee_code').value = oldData.employee_code || admin.employee_code || '';
                document.getElementById('edit_first_name').value = oldData.first_name || admin.first_name;
                document.getElementById('edit_last_name').value = oldData.last_name || admin.last_name;
                document.getElementById('edit_email').value = oldData.email || admin.email;
                document.getElementById('edit_password').value = '';
                
                editForm.action = `/admin/${admin.id}`;
                document.getElementById('editAdminOffcanvasLabel').textContent = `Edit Admin: ${oldData.username || admin.username}`;
                
                // Clear any validation errors
                clearValidationErrors(editForm);

                // Show offcanvas
                if (typeof bootstrap !== 'undefined' && bootstrap.Offcanvas) {
                     new bootstrap.Offcanvas(offcanvasElement).show();
                }
            })
            .catch(error => {
                console.error('Error fetching admin data for edit:', error);
                showNotification('Could not load admin data for editing.', 'danger');
            });
    }

    // --- Search Functionality ---

    function performSearch(searchQuery) {
        const url = new URL(window.location.href);
        if (searchQuery) {
            url.searchParams.set('search', searchQuery);
        } else {
            url.searchParams.delete('search');
        }
        url.searchParams.set('page', '1');

        // Use fetch to get updated table without full page reload
        fetch(url.toString(), {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
            }
        })
        .then(response => response.text())
        .then(html => {
            // Parse the HTML response
            const parser = new DOMParser();
            const doc = parser.parseFromString(html, 'text/html');
            
            // Update table body
            const newTableBody = doc.querySelector('#adminTableBody');
            const currentTableBody = document.getElementById('adminTableBody');
            if (newTableBody && currentTableBody) {
                currentTableBody.innerHTML = newTableBody.innerHTML;
                
                // Re-initialize edit and delete buttons
                initializeTableActions();
            }
            
            // Update pagination
            const newPagination = doc.querySelector('.pagination-container');
            const currentPagination = document.querySelector('.pagination-container');
            const tableContainer = document.querySelector('.table-responsive');

            if (newPagination && currentPagination) {
                currentPagination.innerHTML = newPagination.innerHTML;
            } else if (newPagination && tableContainer) {
                // Add pagination if it doesn't exist
                const paginationDiv = document.createElement('div');
                paginationDiv.className = 'pagination-container p-3 border-top d-flex justify-content-center justify-content-md-end';
                paginationDiv.innerHTML = newPagination.innerHTML;
                tableContainer.insertAdjacentElement('afterend', paginationDiv);
            } else if (currentPagination) {
                // Remove pagination if no results
                currentPagination.remove();
            }

            // Update URL without reload
            window.history.pushState({}, '', url.toString());
            
            // Keep focus on search input (if it exists)
            const searchInput = document.getElementById('adminSearchInput');
            if (searchInput) searchInput.focus();
        })
        .catch(error => {
            console.error('Search error:', error);
            showNotification('An error occurred while searching', 'danger');
        });
    }

    // --- Initialization ---

    function initializeAdminPage() {
        // --- Event Listeners for CRUD and Actions ---
        
        // Initial setup for table actions
        initializeTableActions();

        // Delete confirmation button listener
        const btnDeleteOk = document.getElementById('btn_delete_ok');
        if (btnDeleteOk) {
            btnDeleteOk.addEventListener('click', function() {
                const deleteRecordId = document.getElementById('delete_record_id');
                if (deleteRecordId && deleteRecordId.value) {
                    handleDelete(deleteRecordId.value);
                }
            });
        }

        // Handle Create Form submission
        const createForm = document.getElementById('createAdminForm');
        if (createForm) {
            createForm.addEventListener('submit', function(e) {
                e.preventDefault();
                handleFormSubmit(this, 'Admin user created successfully.', false);
            });
            
            // Clear validation errors when create offcanvas opens
            const createOffcanvas = document.getElementById('createAdminOffcanvas');
            if (createOffcanvas) {
                createOffcanvas.addEventListener('show.bs.offcanvas', function() {
                    clearValidationErrors(createForm);
                    createForm.reset();
                });
            }
        }

        // Handle Edit Form submission
        const editForm = document.getElementById('editAdminForm');
        if (editForm) {
            editForm.addEventListener('submit', function(e) {
                e.preventDefault();
                handleFormSubmit(this, 'Admin user updated successfully.', true);
            });
        }

        // --- Search/Filter Setup ---
        
        const searchInput = document.getElementById('adminSearchInput');
        const clearBtn = document.getElementById('clearSearch');
        let searchTimeout;

        if (searchInput && clearBtn) {
            // Initial clear button state
            if (searchInput.value.trim()) {
                clearBtn.classList.remove('d-none');
            } else {
                clearBtn.classList.add('d-none');
            }

            // Search input listener
            searchInput.addEventListener('input', function() {
                clearTimeout(searchTimeout);
                const value = this.value.trim();
                
                // Show/hide clear button
                if (value) {
                    clearBtn.classList.remove('d-none');
                } else {
                    clearBtn.classList.add('d-none');
                }

                searchTimeout = setTimeout(() => {
                    performSearch(value);
                }, 600);
            });

            // Prevent losing focus on Enter key
            searchInput.addEventListener('keypress', function(e) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    clearTimeout(searchTimeout);
                    performSearch(this.value.trim());
                }
            });

            // Clear button click
            clearBtn.addEventListener('click', function() {
                searchInput.value = '';
                this.classList.add('d-none');
                performSearch('');
            });
        }
        
        // Handle pre-population for validation errors
        const adminIdFromUrl = '{{ request()->get("admin_id") }}'; 
        const oldUsername = '{{ old("username") }}';
        const oldEmployeeCode = '{{ old("employee_code") }}';
        const oldFirstName = '{{ old("first_name") }}';
        const oldLastName = '{{ old("last_name") }}';
        const oldEmail = '{{ old("email") }}';

        if (adminIdFromUrl && adminIdFromUrl.startsWith('{{') === false) { 
            const oldData = {
                username: oldUsername.startsWith('{{') ? null : oldUsername,
                employee_code: oldEmployeeCode.startsWith('{{') ? null : oldEmployeeCode,
                first_name: oldFirstName.startsWith('{{') ? null : oldFirstName,
                last_name: oldLastName.startsWith('{{') ? null : oldLastName,
                email: oldEmail.startsWith('{{') ? null : oldEmail
            };
            fetchAdminAndOpenEditOffcanvas(adminIdFromUrl, oldData);
        }
    }
    
    // --- Initial Execution ---
    
    // Check if the page is still loading or already loaded
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initializeAdminPage);
    } else {
        initializeAdminPage();
    }
})();