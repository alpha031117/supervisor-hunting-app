@extends('layouts.master')

@section('page', 'Coordinator Dashboard')

@section('content')
    <div class="container mx-auto px-4">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Doughnut Chart -->
            <div class="bg-white rounded-lg shadow-md p-4">
                <h5 class="text-center text-lg font-semibold mb-4">Users Distribution</h5>
                <div class="flex justify-center">
                    <canvas id="userChart" class="max-w-lg h-64"></canvas>
                </div>
            </div>

            <!-- Bar Chart -->
            <div class="bg-white rounded-lg shadow-md p-4">
                <h5 class="text-center text-lg font-semibold mb-4">Lecturers in Research Groups</h5>
                <div class="flex justify-center">
                    <canvas id="researchGroupChart" class="max-w-lg h-64"></canvas>
                </div>
            </div>
        </div>

        <!-- Line Chart -->
        <div class="bg-white rounded-lg shadow-md p-4 mt-6">
            <h5 class="text-center text-lg font-semibold mb-4">Applications in Research Groups</h5>
            <div class="flex justify-center">
                <canvas id="applicationChart" class="max-w-full h-64"></canvas>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Doughnut Chart Data
            const userChart = new Chart(document.getElementById('userChart'), {
                type: 'doughnut',
                data: {
                    labels: ['Students', 'Lecturers', 'Coordinators'],
                    datasets: [{
                        label: 'Users',
                        data: [120, 35, 5], // Replace with your dynamic data
                        backgroundColor: ['#4caf50', '#2196f3', '#ff9800'],
                        hoverOffset: 4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom'
                        }
                    }
                }
            });

            // Bar Chart Data
            const researchGroupChart = new Chart(document.getElementById('researchGroupChart'), {
                type: 'bar',
                data: {
                    labels: ['Group A', 'Group B', 'Group C',
                        'Group D'
                    ], // Replace with dynamic group names
                    datasets: [{
                        label: 'Lecturers',
                        data: [10, 15, 5, 8], // Replace with your dynamic data
                        backgroundColor: ['#2196f3', '#4caf50', '#ff9800', '#f44336']
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            title: {
                                display: true,
                                text: 'Number of Lecturers'
                            }
                        },
                        x: {
                            title: {
                                display: true,
                                text: 'Research Groups'
                            }
                        }
                    }
                }
            });

            // Line Chart Data
            const applicationChart = new Chart(document.getElementById('applicationChart'), {
                type: 'line',
                data: {
                    labels: ['Group A', 'Group B', 'Group C',
                        'Group D'
                    ], // Replace with dynamic group names
                    datasets: [{
                        label: 'Applications',
                        data: [25, 40, 30, 20], // Replace with your dynamic data
                        borderColor: '#4caf50',
                        backgroundColor: 'rgba(76, 175, 80, 0.1)',
                        borderWidth: 2,
                        fill: true,
                        tension: 0.4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'top'
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            title: {
                                display: true,
                                text: 'Total Applications'
                            }
                        },
                        x: {
                            title: {
                                display: true,
                                text: 'Research Groups'
                            }
                        }
                    }
                }
            });
        });
    </script>
@endsection
