<div class="offcanvas offcanvas-end" tabindex="-1" id="createAdminOffcanvas" aria-labelledby="createAdminOffcanvasLabel">
    <div class="offcanvas-header text-white border-bottom">
        <h5 class="offcanvas-title fw-medium" id="createAdminOffcanvasLabel">Add Admin</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body">
        <form action="{{ route('admin.store') }}" method="POST" autocomplete="off" id="createAdminForm">
            @csrf
            <div class="mb-3 mt-3">
                <label for="create_username" class="form-label fw-medium">Username</label>
                <input type="text" 
                       class="form-control @error('username') is-invalid @enderror" 
                       id="create_username" 
                       name="username" 
                       placeholder="Enter username" 
                       value="{{ old('username') }}"
                       required>
                @error('username')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="create_employee_code" class="form-label fw-medium">Emp Code</label>
                <input type="text" 
                       class="form-control @error('employee_code') is-invalid @enderror" 
                       id="create_employee_code" 
                       name="employee_code" 
                       placeholder="Enter employee code" 
                       value="{{ old('employee_code') }}"
                       required>
                @error('employee_code')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="create_first_name" class="form-label fw-medium">First Name</label>
                <input type="text" 
                       class="form-control @error('first_name') is-invalid @enderror" 
                       id="create_first_name" 
                       name="first_name" 
                       placeholder="Enter first name" 
                       value="{{ old('first_name') }}"
                       required>
                @error('first_name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="create_last_name" class="form-label fw-medium">Last Name</label>
                <input type="text" 
                       class="form-control @error('last_name') is-invalid @enderror" 
                       id="create_last_name" 
                       name="last_name" 
                       placeholder="Enter last name" 
                       value="{{ old('last_name') }}"
                       required>
                @error('last_name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="create_email" class="form-label fw-medium">Email</label>
                <input type="email" 
                       class="form-control @error('email') is-invalid @enderror" 
                       id="create_email" 
                       name="email" 
                       placeholder="Enter email address" 
                       value="{{ old('email') }}"
                       required>
                @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="create_password" class="form-label fw-medium">Password</label>
                <input type="text" 
                       class="form-control @error('password') is-invalid @enderror" 
                       id="create_password" 
                       name="password" 
                       placeholder="Enter password" 
                       value="{{ old('password', 'Ocean01!') }}"
                       required>
                <div class="form-text">Default password: Ocean01!</div>
                @error('password')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="d-flex justify-content-end gap-2 mt-5">
                <button type="button" class="btn btn-light" data-bs-dismiss="offcanvas">Cancel</button>
                <button type="submit" class="btn btn-primary">Create Admin</button>
            </div>
        </form>
    </div>
</div>