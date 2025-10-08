@extends('layouts.app')

@section('content')

    {{--
    ************************************************************************
    LARAVEL DATA SIMULATION
    In a real application, this data would be passed from your controller
    via compact or with(). We define it here for runnable example purposes.
    ************************************************************************
    --}}
    @php
        // Data for the Bar Chart
        $departmentData = [
            ['name' => 'Tech', 'value' => 150, 'color' => '#002E6E'],
            ['name' => 'Sales', 'value' => 90, 'color' => '#004AB3'],
            ['name' => 'Customer Service', 'value' => 50, 'color' => '#007EE6'],
            ['name' => 'Design', 'value' => 75, 'color' => '#1AB3FF'],
        ];

        // Data for the Pie/Doughnut Chart
        $overviewData = [
            ['name' => 'Back Office', 'value' => 52.1, 'color' => '#002E6E'],
            ['name' => 'Sales', 'value' => 22.8, 'color' => '#004AB3'],
            ['name' => 'Customer Service', 'value' => 13.9, 'color' => '#007EE6'],
            ['name' => 'Specialized Accountant', 'value' => 11.2, 'color' => '#1AB3FF'],
        ];
    @endphp

    <script>
        // Pass the PHP data to a global JavaScript variable now that it is defined.
        window.dashboardData = {
            department: {!! json_encode($departmentData) !!},
            overview: {!! json_encode($overviewData) !!}
        };
    </script>

    <div id="dashboard" class="py-6 sm:py-12">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            <h1> Dashboard </h1>
            <p> Welcome to your personality test management system </p>

            {{-- Top Cards Row --}}
            <div class="row gx-3">
                <div class="col-md-4">
                    <div class="card mb-3 shadow-sm bg-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <h6 class="card-subtitle mb-2 text-uppercase">Total Users</h6>
                                    <h2 class="card-text fw-bold">1234</h2>
                                    <p class="card-text text-muted">Active test participants</p>
                                </div>
                                <i class="bi bi-person text-dark h3"></i>
                            </div>
                        </div>
                        <div class="card-footer-blue"></div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card shadow-sm bg-white mb-3">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <h6 class="card-subtitle mb-2 text-uppercase">Test Completed</h6>
                                    <h2 class="card-text fw-bold">567</h2>
                                    <p class="card-text text-muted">Successfully finished assessments</p>
                                </div>
                                <i class="bi bi-check-circle text-dark h3"></i>
                            </div>
                        </div>
                        <div class="card-footer-blue"></div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card shadow-sm bg-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <h6 class="card-subtitle mb-2 text-uppercase">Active Tests</h6>
                                    <h2 class="card-text fw-bold">89</h2>
                                    <p class="card-text text-muted">Currently in progress</p>
                                </div>
                                <i class="bi bi-clock text-dark h3"></i>
                            </div>
                        </div>
                        <div class="card-footer-blue"></div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Chart Row (Converted from original code) --}}
        <div class="row g-4 mt-4 px-4">
            {{-- Bar Chart Card: Candidate Distribution by Department --}}
            <div class="col-12 col-lg-6">
                <div class="card shadow-sm bg-white h-100">
                    <div class="card-header bg-white border-0">
                        <h5 class="card-title fw-semibold mb-3 mt-3">
                            Candidate Distribution by Department
                        </h5>
                    </div>
                    <div class="card-body pt-0">
                        {{-- Chart.js canvas --}}
                        <div class="barcanvas">
                            <canvas id="departmentBarChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Pie Chart Card: Candidate Distribution Overview (Doughnut style with legend) --}}
            <div class="col-12 col-lg-6">
                <div class="card shadow-sm bg-white h-100">
                    <div class="card-header bg-white border-0">
                        <h5 class="card-title fw-semibold mt-3 mb-3">Candidate Distribution Overview</h5>
                    </div>
                    <div class="card-body pt-0 d-flex flex-column flex-md-row justify-content-center align-items-center">

                        {{-- Doughnut Chart Area --}}
                        <div class="doughnut-chart-container">
                            <canvas id="overviewDoughnutChart"></canvas>
                        </div>

                        {{-- Legend/Breakdown List --}}
                        <div class="d-flex flex-column ms-5 mt-4 mt-md-0">
                            @foreach ($overviewData as $item)
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <div class="d-flex align-items-center">
                                        <div class="position-circle rounded-circle me-2" data-color="{{ $item['color'] }}">
                                        </div>
                                        <span class="text-sm me-5">{{ $item['name'] }}</span>
                                    </div>
                                    <span class="text-sm fw-medium text-end">{{ $item['value'] }}%</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@vite(['resources/js/dashboard.js'])
@endsection