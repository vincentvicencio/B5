
    document.addEventListener('DOMContentLoaded', function() {
        const editOffcanvasEl = document.getElementById('editAdminOffcanvas');
        if (editOffcanvasEl) {
            const editOffcanvas = new bootstrap.Offcanvas(editOffcanvasEl);
            const adminId = '{{ request()->get("admin_id") }}';
            if (adminId) {
                fetch(`/admin/${adminId}/edit`)
                    .then(response => response.json())
                    .then(admin => {
                        document.getElementById('edit_admin_id').value = admin.id;
                        document.getElementById('edit_username').value = '{{ old("username") }}' || admin.username;
                        document.getElementById('edit_employee_code').value = '{{ old("employee_code") }}' || admin.employee_code;
                        document.getElementById('edit_first_name').value = '{{ old("first_name") }}' || admin.first_name;
                        document.getElementById('edit_last_name').value = '{{ old("last_name") }}' || admin.last_name;
                        document.getElementById('edit_email').value = '{{ old("email") }}' || admin.email;
                        document.getElementById('editAdminForm').action = `/admin/${admin.id}`;
                        document.getElementById('editAdminOffcanvasLabel').textContent = `Edit Admin: ${admin.username}`;
                        editOffcanvas.show();
                    });
            }
        }
    });
