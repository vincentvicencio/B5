@extends('layouts.app')

@section('content')

    <div id="respondents" class="py-6 sm:py-12">

        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">

            <div class="container-fluid header-content">
                <div class="header-text">
                    <h1> Respondents </h1>
                    <p> View and manage assessment respondents and their results </p>
                </div>
                <button id="export-btn" class="btn"> <i class="bi bi-download"></i> Export </button>
            </div>

            <div class="container-fluid py-2">
                <div class="row justify-content-center">
                    <div class="col-12 col-md-12 col-lg-12">
                        <div class="p-4 bg-white rounded-3 shadow-sm border search-container">
                            <div class="row g-2">
                                <div class="col-md-6 col-lg-6">
                                    <div class="input-group">
                                        <span class="input-group-text border-end-0">
                                            <i class="bi bi-search"></i>
                                        </span>
                                        <input type="text" id="name-email-search" class="form-control border-start-0"
                                            placeholder="Search by name, email ....">
                                    </div>
                                </div>

                                <div class="col-md-3 col-lg-3">
                                    <div class="input-group dropdown">
                                        <span class="input-group-text border-end-0">
                                            <i class="bi bi-filter"></i>
                                        </span>
                                        <div id="interpretation-filter"
                                            class="form-control border-start-0 d-flex justify-content-between align-items-center"
                                            data-bs-toggle="dropdown" aria-expanded="false">
                                            <span id="interpretation-placeholder">All Interpretation</span>
                                            <i class="bi bi-chevron-down"></i>
                                        </div>
                                        <ul class="dropdown-menu w-100 bg-white" id="interpretation-menu">
                                            <li><a class="dropdown-item" href="#">All Interpretation</a></li>
                                            <li><hr class="dropdown-divider"></li>
                                            <li><a class="dropdown-item" href="#">High</a></li>
                                            <li><a class="dropdown-item" href="#">Moderate</a></li>
                                            <li><a class="dropdown-item" href="#">Low</a></li>
                                        </ul>
                                    </div>
                                </div>

                                <div class="col-md-3 col-lg-3">
                                    <div class="input-group dropdown">
                                        <span class="input-group-text border-end-0">
                                            <i class="bi bi-filter"></i>
                                        </span>
                                        <div id="recommendation-filter"
                                            class="form-control border-start-0 d-flex justify-content-between align-items-center"
                                            data-bs-toggle="dropdown" aria-expanded="false">
                                            <span id="recommendation-placeholder">All Recommendation</span>
                                            <i class="bi bi-chevron-down"></i>
                                        </div>
                                        <ul class="dropdown-menu w-100 bg-white" id="recommendation-menu">
                                            <li><a class="dropdown-item" href="#">All Recommendation</a></li>
                                            <li><hr class="dropdown-divider"></li>
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

            <div class="table-card table-card-fixed-height py-4">
                <div class="table-container table-responsive-sm table-responsive-md table-responsive-lg">
                    <table class="table modern-table" id="registeredidTable">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Interpretation</th>
                                <th>Recommendation</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr data-interpretation="High" data-recommendation="Sales">
                                <td>John Doe</td>
                                <td>john.doe@example.com</td>
                                <td>High</td>
                                <td>Sales</td>
                            </tr>
                            <tr data-interpretation="Moderate" data-recommendation="Back Office">
                                <td>Jane Smith</td>
                                <td>jane.smith@example.com</td>
                                <td>Moderate</td>
                                <td>Back Office</td>
                            </tr>
                            <tr data-interpretation="Low" data-recommendation="Customer Service">
                                <td>Peter Jones</td>
                                <td>peter.jones@example.com</td>
                                <td>Low</td>
                                <td>Customer Service</td>
                            </tr>
                            <tr data-interpretation="High" data-recommendation="Specialized Accountant">
                                <td>Sarah Lee</td>
                                <td>sarah.lee@example.com</td>
                                <td>High</td>
                                <td>Specialized Accountant</td>
                            </tr>
                            <tr data-interpretation="Moderate" data-recommendation="Sales">
                                <td>Michael Chen</td>
                                <td>michael.chen@example.com</td>
                                <td>Moderate</td>
                                <td>Sales</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>

@endsection