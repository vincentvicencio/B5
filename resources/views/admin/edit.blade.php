<!-- Offcanvas for Edit Admin -->
<div class="offcanvas offcanvas-end" tabindex="-1" id="editAdminOffcanvas" aria-labelledby="editAdminOffcanvasLabel">
    <div class="offcanvas-header text-white border-bottom">
        <h5 class="offcanvas-title fw-medium" id="editAdminOffcanvasLabel">Edit Admin</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body">
        <form action="" method="POST" autocomplete="off" id="editAdminForm">
            @csrf
            @method('PUT')

            <input type="hidden" id="edit_admin_id" name="admin_id">

            <div class="mb-3 mt-3">
                <label for="edit_username" class="form-label fw-medium">Username</label>
                <input type="text" 
                       class="form-control @error('username') is-invalid @enderror" 
                       id="edit_username" 
                       name="username" 
                       placeholder="Enter username" 
                       value="{{ old('username') }}"
                       required>
                @error('username')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="edit_employee_code" class="form-label fw-medium">Employee Code</label>
                <input type="text" 
                       class="form-control @error('employee_code') is-invalid @enderror" 
                       id="edit_employee_code" 
                       name="employee_code" 
                       placeholder="Enter employee code" 
                       value="{{ old('employee_code') }}"
                       required>
                @error('employee_code')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="edit_first_name" class="form-label fw-medium">First Name</label>
                <input type="text" 
                       class="form-control @error('first_name') is-invalid @enderror" 
                       id="edit_first_name" 
                       name="first_name" 
                       placeholder="Enter first name" 
                       value="{{ old('first_name') }}"
                       required>
                @error('first_name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="edit_last_name" class="form-label fw-medium">Last Name</label>
                <input type="text" 
                       class="form-control @error('last_name') is-invalid @enderror" 
                       id="edit_last_name" 
                       name="last_name" 
                       placeholder="Enter last name" 
                       value="{{ old('last_name') }}"
                       required>
                @error('last_name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="edit_email" class="form-label fw-medium">Email</label>
                <input type="email" 
                       class="form-control @error('email') is-invalid @enderror" 
                       id="edit_email" 
                       name="email" 
                       placeholder="Enter email address" 
                       value="{{ old('email') }}"
                       required>
                @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="edit_password" class="form-label fw-medium">Password</label>
                <div class="input-group">
                    <input type="password" 
                           class="form-control @error('password') is-invalid @enderror" 
                           id="edit_password" 
                           name="password" 
                           placeholder="Enter new password" 
                           autocomplete="new-password">
                    <button class="btn btn-outline-secondary" type="button" id="toggleEditPassword">
                        <i class="bi bi-eye-slash" id="editPasswordIcon"></i>
                    </button>
                </div>
                <div class="form-text text-muted">Leave blank to keep the existing password.</div>
                @error('password')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
            </div>

            <div class="d-flex justify-content-end gap-2 mt-5">
                <button type="button" class="btn btn-light" data-bs-dismiss="offcanvas">Cancel</button>
                <button type="submit" class="btn btn-primary">
                Update Admin
                </button>
            </div>
        </form>
    </div>
@vite(['resources/sass/app.scss', 'resources/js/app.js'])
</div>

<script>
// Password toggle functionality for edit form
document.addEventListener('DOMContentLoaded', function() {
    const toggleEditPassword = document.getElementById('toggleEditPassword');
    const editPassword = document.getElementById('edit_password');
    const editPasswordIcon = document.getElementById('editPasswordIcon');

    if (toggleEditPassword) {
        toggleEditPassword.addEventListener('click', function() {
            // Toggle password visibility
            const type = editPassword.getAttribute('type') === 'password' ? 'text' : 'password';
            editPassword.setAttribute('type', type);
            
            // Toggle icon
            if (type === 'password') {
                editPasswordIcon.classList.remove('bi-eye');
                editPasswordIcon.classList.add('bi-eye-slash');
            } else {
                editPasswordIcon.classList.remove('bi-eye-slash');
                editPasswordIcon.classList.add('bi-eye');
            }
        });
    }
});
</script>