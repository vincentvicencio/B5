@extends('layouts.app')

@section('content')


    <div id="respondents" class="py-6 sm:py-12">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">

            <div class="header-content">
                <div class="row w-100 g-2">
                    <div class="col-12 col-sm-8 col-md-9 header-text">
                        <h1> Respondents </h1>
                        <p> View and manage assessment respondents and their results </p>
                    </div>
                    <div class="col-12 col-sm-4 col-md-3 d-flex justify-content-sm-end justify-content-lg-end">
                        <button id="export-btn" class="btn w-100 w-sm-auto w-lg-auto">
                            <i class="bi bi-download"></i> Export
                        </button>
                    </div>
                </div>
            </div>

            <div class="py-2">
                <div class="row justify-content-center">
                    <div class="col-12 col-md-12 col-lg-12">
                        <div class="p-4 bg-white rounded-3 shadow-sm border search-container">
                            <div class="row g-2 align-items-center">
                                <div class="col-12 col-md-12 col-lg-4">
                                    <div class="input-group">
                                        <i id="icon-design" class="bi bi-search text-muted position-absolute"></i>
                                        <input type="text" id="name-email-search" class="form-control border-start-0"
                                            placeholder="Search by name, email ....">

                                    </div>
                                </div>
                                <div class="col-12 col-md-6 col-lg-4">
                                    <div class="input-group dropdown">
                                        <i id="icon-design" class="bi bi-filter text-muted position-absolute"></i>
                                        <div id="interpretation-filter"
                                            class="form-control border-start-0 d-flex justify-content-between align-items-center"
                                            data-bs-toggle="dropdown" aria-expanded="false">
                                            <span id="interpretation-placeholder">All Interpretation</span>
                                            <i class="bi bi-chevron-down"></i>
                                        </div>
                                        <ul class="dropdown-menu w-100 bg-white" id="interpretation-menu">
                                            <li><a class="dropdown-item" href="#">All Interpretation</a></li>
                                            <li>
                                                <hr class="dropdown-divider">
                                            </li>
                                            <li><a class="dropdown-item" href="#">High</a></li>
                                            <li><a class="dropdown-item" href="#">Moderate</a></li>
                                            <li><a class="dropdown-item" href="#">Low</a></li>
                                        </ul>
                                    </div>
                                </div>

                                <div class="col-12 col-md-6 col-lg-4">
                                    <div class="input-group dropdown">
                                        <i id="icon-design" class="bi bi-filter text-muted position-absolute"></i>
                                        <div id="recommendation-filter"
                                            class="form-control border-start-0 d-flex justify-content-between align-items-center"
                                            data-bs-toggle="dropdown" aria-expanded="false">
                                            <span id="recommendation-placeholder">All Recommendation</span>
                                            <i class="bi bi-chevron-down"></i>
                                        </div>
                                        <ul class="dropdown-menu w-100 bg-white" id="recommendation-menu">
                                            <li><a class="dropdown-item" href="#">All Recommendation</a></li>
                                            <li>
                                                <hr class="dropdown-divider">
                                            </li>
                                            <li><a class="dropdown-item" href="#">Back Office</a></li>
                                            <li><a class="dropdown-item" href="#">Sales</a></li>
                                            <li><a class="dropdown-item" href="#">Customer Service</a></li>
                                            <li><a class="dropdown-item" href="#">Specialized Accountant</a></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            @if (session('error'))
                <div class="alert alert-danger mt-3">
                    {{ session('error') }}
                </div>
            @endif
            <!-- Respondents Table Container -->
            <div class="py-4">
                <div class="table-responsive bg-white rounded-3 shadow-sm border p-4">
                    <table class="table table-hover align-middle mb-0" id="respondents-table">
                        <thead>
                            <tr class="text-uppercase text-secondary" id="respondents-table-header">
                                {{-- These are the fixed columns. Dynamic trait columns will be injected by JavaScript. --}}
                                <th scope="col">#</th>
                                <th scope="col">Respondent</th>
                                {{-- Dynamic Trait Headers will be inserted here by respondents.js based on API response
                                --}}
                                <th scope="col">Overall Score</th>
                                <th scope="col">Interpretation</th>
                                <th scope="col">Action</th>
                            </tr>
                        </thead>
                        <tbody id="respondents-table-body">
                            <!-- Data will be loaded via AJAX/JavaScript here -->
                            <tr>
                                {{-- colspan will be updated by JS once trait count is known --}}
                                <td colspan="10" class="text-center py-5 text-muted">Loading respondents data...</td>
                            </tr>
                        </tbody>
                    </table>

                    <!-- Placeholder for pagination/footer info -->
                    <div id="table-footer" class="d-flex justify-content-between align-items-center pt-3">
                        <span id="record-info" class="text-muted small"></span>
                        <nav aria-label="Table Pagination" id="pagination-controls">
                            <!-- Pagination will be inserted here -->
                        </nav>
                    </div>
                </div>
            </div>

        </div>
    </div>
    @include('components.confirmation')
    @include('components.toast')
    @vite(['resources/js/search.js', 'resources/js/respondents.js'])
@endsection