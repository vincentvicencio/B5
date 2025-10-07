@extends('layouts.app')

@section('content')
    <div class="container-fluid px-2 px-md-4">
        <div class="max-w-5xl mx-auto">

            <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center admin-header mb-3 mb-md-4">
                <div class="mb-3 mb-md-0">
                    <h1 class="mb-1">Admin Management</h1>
                    <p class="text-muted fs-6 mb-0">Manage administrator accounts and add new admins to the system</p>
                </div>
                <button class="btn btn-primary shadow-sm" type="button" data-bs-toggle="offcanvas" data-bs-target="#createAdminOffcanvas">
                    <i class="bi bi-person-plus-fill me-2"></i>Add Admin
                </button>
            </div>

            <div class="search p-2 p-md-3 d-flex justify-content-center border-bottom">
                <div class="col-12 col-md-6 col-lg-4 w-100">
                    <div class="position-relative">
                        <input type="text" 
                            class="form-control form-control-sm" 
                            placeholder="Search username, emp code..." 
                            value="{{ request('search') }}"
                            id="adminSearchInput"
                            autocomplete="off">
                        <i class="bi bi-search text-muted position-absolute"></i>
                        <button type="button" 
                                class="btn btn-sm position-absolute {{ request('search') ? '' : 'd-none' }}" 
                                id="clearSearch">
                            <i class="bi bi-x-circle-fill text-secondary"></i>
                        </button>
                    </div>
                </div>
            </div>

            <div class="card shadow-sm">
                <div class="card-body p-0 m-2">
                    <div class="table-container">
                        <table class="table table-hover align-middle mb-0 table-sm">
                            <thead>
                                <tr>
                                    <th class="first-row py-2 px-3">#</th>
                                    <th class="py-2">Username</th>
                                    <th class="py-2">Emp Code</th>
                                    <th class="py-2">First Name</th>
                                    <th class="py-2">Last Name</th>
                                    <th class="py-2">Email</th>
                                    <th class="py-2">Updated By</th>
                                    <th class="py-2">Updated Date</th>
                                    <th class="py-2 text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody id="adminTableBody">
                                @forelse($admins as $index => $admin)
                                <tr>
                                    <td class="py-2 px-3">{{ $admins->firstItem() + $index }}</td>
                                    <td class="py-2">{{ $admin->username }}</td>
                                    <td class="py-2">{{ $admin->employee_code ?? '-' }}</td>
                                    <td class="py-2">{{ $admin->first_name }}</td>
                                    <td class="py-2">{{ $admin->last_name }}</td>
                                    <td class="py-2">{{ $admin->email }}</td>
                                    <td class="py-2">{{ $admin->updated_by ?? 'SuperAdmin' }}</td>
                                    <td class="py-2">{{ $admin->updated_at->format('m/d/Y') }}</td>
                                    <td class="py-2 text-center">
                                        <div class="dropdown">
                                            <button class="btn btn-sm p-1 dropdown-toggle-custom" type="button" data-bs-toggle="dropdown" data-bs-display="static">
                                                <i class="bi bi-three-dots-vertical"></i>
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-end">
                                                <li>
                                                    <a class="dropdown-item edit-btn" href="#" data-id="{{ $admin->id }}">
                                                        <i class="bi bi-pencil me-2"></i>Edit
                                                    </a>
                                                </li>
                                                <li><hr class="dropdown-divider"></li>
                                                <li>
                                                    <form action="{{ route('admin.destroy', $admin->id) }}" method="POST" class="delete-form d-inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="dropdown-item delete-btn"> 
                                                            <i class="bi bi-trash-fill me-2"></i>Delete
                                                        </button>
                                                    </form>
                                                </li>
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="9" class="text-center text-muted py-4">
                                        @if(request('search'))
                                            No admins found matching "{{ request('search') }}".
                                        @else
                                            No admin accounts found.
                                        @endif
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @if($admins->hasPages())
                    <div class="pagination-container p-3 border-top d-flex justify-content-center justify-content-md-end">
                        {{ $admins->links('pagination::bootstrap-5') }}
                    </div>
                    @endif
                </div>
            </div>
        </div>

        @vite(['resources/js/admin.js'])
        {{-- @vite(['resources/sass/app.scss', 'resources/js/app.js']) --}}
    </div>

    @include('admin.create')
    @include('admin.edit')
@endsection