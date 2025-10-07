// resources/js/admin.js
(function() {
    'use strict';

    function showNotification(message, type = 'success') {
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

    // Add styles is removed - use external CSS file instead
    
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
        const row = document.querySelector(`tr:has(a.edit-btn[data-id="${admin.id}"])`);
        if (row) {
            const cells = row.querySelectorAll('td');
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
        }
    }

    function handleFormSubmit(form, successMessage, isUpdate = false) {
        clearValidationErrors(form);
        
        const formData = new FormData(form);
        const actionUrl = form.action;
        
        // Disable submit button
        const submitBtn = form.querySelector('button[type="submit"]');
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
                
                // Show notification at top right
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
                showNotification('An error occurred while processing your request', 'danger');
            }
        })
        .finally(() => {
            if (submitBtn) {
                submitBtn.disabled = false;
                submitBtn.innerHTML = isUpdate ? 'Update Admin' : 'Create Admin';
            }
        });
    }

    function initializeAdminPage() {
        // Edit button
        document.querySelectorAll('.edit-btn').forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                fetch('/admin/' + this.dataset.id + '/edit')
                    .then(r => r.json())
                    .then(admin => {
                        document.getElementById('edit_admin_id').value = admin.id;
                        document.getElementById('edit_username').value = admin.username;
                        document.getElementById('edit_employee_code').value = admin.employee_code || '';
                        document.getElementById('edit_first_name').value = admin.first_name;
                        document.getElementById('edit_last_name').value = admin.last_name;
                        document.getElementById('edit_email').value = admin.email;
                        document.getElementById('edit_password').value = '';
                        document.getElementById('editAdminForm').action = '/admin/' + admin.id;
                        document.getElementById('editAdminOffcanvasLabel').textContent = 'Edit Admin: ' + admin.username;
                        
                        // Clear any validation errors
                        const editForm = document.getElementById('editAdminForm');
                        clearValidationErrors(editForm);
                        
                        new bootstrap.Offcanvas(document.getElementById('editAdminOffcanvas')).show();
                    });
            });
        });


        // Handle Create Form
        const createForm = document.getElementById('createAdminForm');
        if (createForm) {
            createForm.addEventListener('submit', function(e) {
                e.preventDefault();
                handleFormSubmit(this, 'Admin user created successfully.', false);
            });
            
            // Clear validation errors when offcanvas opens
            const createOffcanvas = document.getElementById('createAdminOffcanvas');
            if (createOffcanvas) {
                createOffcanvas.addEventListener('show.bs.offcanvas', function() {
                    clearValidationErrors(createForm);
                    createForm.reset();
                });
            }
        }

        // Handle Edit Form
        const editForm = document.getElementById('editAdminForm');
        if (editForm) {
            editForm.addEventListener('submit', function(e) {
                e.preventDefault();
                handleFormSubmit(this, 'Admin user updated successfully.', true);
            });
        }

        // Search with X button - AJAX version (no page reload)
        const searchInput = document.getElementById('adminSearchInput');
        const clearBtn = document.getElementById('clearSearch');
        let searchTimeout;

        if (searchInput) {
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
        }

        // Clear button click
        if (clearBtn) {
            clearBtn.addEventListener('click', function() {
                searchInput.value = '';
                this.classList.add('d-none');
                performSearch('');
            });
        }

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
                if (newTableBody) {
                    document.getElementById('adminTableBody').innerHTML = newTableBody.innerHTML;
                    
                    // Re-initialize edit and delete buttons
                    initializeTableActions();
                }
                
                // Update pagination
                const newPagination = doc.querySelector('.pagination-container');
                const currentPagination = document.querySelector('.pagination-container');
                if (newPagination && currentPagination) {
                    currentPagination.innerHTML = newPagination.innerHTML;
                } else if (newPagination) {
                    // Add pagination if it doesn't exist
                    const cardBody = document.querySelector('.card-body');
                    const paginationDiv = document.createElement('div');
                    paginationDiv.className = 'pagination-container p-3 border-top d-flex justify-content-center justify-content-md-end';
                    paginationDiv.innerHTML = newPagination.innerHTML;
                    cardBody.appendChild(paginationDiv);
                } else if (currentPagination) {
                    // Remove pagination if no results
                    currentPagination.remove();
                }

                // Update URL without reload
                window.history.pushState({}, '', url.toString());
                
                // Keep focus on search input
                searchInput.focus();
            })
            .catch(error => {
                console.error('Search error:', error);
                showNotification('An error occurred while searching', 'danger');
            });
        }

        function initializeTableActions() {
            // Re-initialize edit buttons
            document.querySelectorAll('.edit-btn').forEach(btn => {
                btn.addEventListener('click', function(e) {
                    e.preventDefault();
                    fetch('/admin/' + this.dataset.id + '/edit')
                        .then(r => r.json())
                        .then(admin => {
                            document.getElementById('edit_admin_id').value = admin.id;
                            document.getElementById('edit_username').value = admin.username;
                            document.getElementById('edit_employee_code').value = admin.employee_code || '';
                            document.getElementById('edit_first_name').value = admin.first_name;
                            document.getElementById('edit_last_name').value = admin.last_name;
                            document.getElementById('edit_email').value = admin.email;
                            document.getElementById('edit_password').value = '';
                            document.getElementById('editAdminForm').action = '/admin/' + admin.id;
                            document.getElementById('editAdminOffcanvasLabel').textContent = 'Edit Admin: ' + admin.username;
                            
                            const editForm = document.getElementById('editAdminForm');
                            clearValidationErrors(editForm);
                            
                            new bootstrap.Offcanvas(document.getElementById('editAdminOffcanvas')).show();
                        });
                });
            });

            // Re-initialize delete forms
        //     document.querySelectorAll('.delete-form').forEach(form => {
        //         form.addEventListener('submit', function(e) {
        //             e.preventDefault();
                    
        //             if (confirm('Are you sure you want to delete this admin account? This action cannot be undone.')) {
        //                 const formData = new FormData(this);
        //                 const actionUrl = this.action;
                        
        //                 fetch(actionUrl, {
        //                     method: 'POST',
        //                     body: formData,
        //                     headers: {
        //                         'X-Requested-With': 'XMLHttpRequest',
        //                         'Accept': 'application/json',
        //                     }
        //                 })
        //                 .then(response => response.json())
        //                 .then(data => {
        //                     if (data.success) {
        //                         showNotification(data.message, 'success');
        //                         setTimeout(() => {
        //                             window.location.reload();
        //                         }, 1000);
        //                     } else {
        //                         showNotification(data.message || 'An error occurred', 'danger');
        //                     }
        //                 })
        //                 .catch(error => {
        //                     console.error('Error:', error);
        //                     showNotification('An error occurred while deleting the admin', 'danger');
        //                 });
        //             }
        //         });
        //     });
        }
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initializeAdminPage);
    } else {
        initializeAdminPage();
    }
})();