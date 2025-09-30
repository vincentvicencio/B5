// resources/js/dashboard.js

// This file contains the logic for the dashboard page.

document.addEventListener('DOMContentLoaded', function () {

    // Check if the dashboardData object exists before proceeding.
    if (window.dashboardData) {

        // Get the data from the global window object.
        const departmentData = window.dashboardData.department;
        const overviewData = window.dashboardData.overview;

        // ------------------------------------
        // 1. Bar Chart Initialization
        // ------------------------------------
        const deptCtx = document.getElementById('departmentBarChart');
        if (deptCtx) {
            new Chart(deptCtx, {
                type: 'bar',
                data: {
                    labels: departmentData.map(d => d.name),
                    datasets: [{
                        label: 'Candidates',
                        data: departmentData.map(d => d.value),
                        backgroundColor: departmentData.map(d => d.color),
                        borderRadius: 4,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            callbacks: {
                                title: (context) => context[0].label,
                                label: (context) => `${context.dataset.label}: ${context.raw}`
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: { color: 'rgba(0, 0, 0, 0.05)', borderColor: 'rgba(0, 0, 0, 0.1)' }
                        },
                        x: {
                            grid: { display: false }
                        }
                    }
                }
            });
        }


        // ------------------------------------
        // 2. Doughnut Chart Initialization
        // ------------------------------------
        const overviewCtx = document.getElementById('overviewDoughnutChart');
        if (overviewCtx) {
            new Chart(overviewCtx, {
                type: 'doughnut',
                data: {
                    labels: overviewData.map(d => d.name),
                    datasets: [{
                        data: overviewData.map(d => d.value),
                        backgroundColor: overviewData.map(d => d.color),
                        borderColor: '#ffffff',
                        borderWidth: 2,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: '50%',
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            callbacks: {
                                label: (context) => `${context.label}: ${context.raw}%`
                            }
                        }
                    }
                }
            });
        }

        // ------------------------------------
        // 3. Set dynamic background colors
        // ------------------------------------
        document.querySelectorAll('.position-circle').forEach(circle => {
            const color = circle.getAttribute('data-color');
            circle.style.backgroundColor = color;
        });
    }

});