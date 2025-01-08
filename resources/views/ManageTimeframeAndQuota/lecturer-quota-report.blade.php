@extends('layouts.master')

@section('page', 'Lecturer Quota Report')

@section('breadcrumbs')
    <a href="{{ route('coordinator.dashboard') }}" class="hover:text-blue-600">
        <li>FYP Hunt</li>
    </a>
    <li>/</li>
    <a href="{{ route('manage-lecturer-quota') }}" class="hover:text-blue-600">
        <li>Manage Lecturer Quota</li>
    </a>
    <li>/</li>
    <a href="{{ route('lecturer-quota-report') }}" class="text-blue-600">
        <li class="text-blue-600">Quota Report</li>
    </a>
@endsection

@section('content')
    <div class="flex items-center justify-between mb-6 mt-10">
        <!-- Left Group: Semester Filter & Filter Button -->
        <div class="flex items-center space-x-4">
            <!-- Semester Filter -->
            {{-- <select id="semester"
                class="border-b border-blue-500 text-blue-500 px-4 py-2 rounded-lg bg-white hover:bg-blue-50">
                <option value="">All Semesters</option>
                @foreach ($semesters as $semester)
                    <option value="{{ $semester }}">{{ $semester }}</option>
                @endforeach
            </select> --}}
            <select id="semester"
                class="border-b border-blue-500 text-blue-500 px-4 py-2 rounded-lg bg-white hover:bg-blue-50">
                <option value="">All Semesters</option>
                @foreach ($semesters as $semester)
                    <option value="{{ $semester }}">{{ $semester }}</option>
                @endforeach
            </select>

            <button id="filter-semester-button"
                class="text-white bg-blue-700 shadow-lg hover:bg-blue-800 px-8 py-2 rounded-lg">
                Filter
            </button>
        </div>

        <!-- Right: Generate Report Button -->
        <button id="generate-report"
            class="border border-blue-500 text-blue-500 px-4 py-2 rounded-lg bg-white hover:bg-blue-50">
            Generate Report
        </button>
    </div>

    <!-- Stats Section -->
    <div class="grid grid-cols-3 gap-6 mb-6">
        <div class="p-4 bg-white rounded shadow">
            <p class="text-gray-600">Total Lecturers</p>
            <h2 id="total-lecturers" class="text-xl font-bold text-gray-800">0</h2>
        </div>
        <div class="p-4 bg-white rounded shadow">
            <p class="text-gray-600">Total Quota</p>
            <h2 id="total-quota" class="text-xl font-bold text-gray-800">0</h2>
        </div>
        <div class="p-4 bg-white rounded shadow">
            <p class="text-gray-600">Available Quota</p>
            <h2 id="available-quota" class="text-xl font-bold text-gray-800">0</h2>
        </div>
    </div>

    <!-- Table -->
    <div class="overflow-x-auto">
        <table id="quota-table" class="table-auto w-full text-left">
            <thead class="bg-gray-100">
                <tr>
                    <th class="px-4 py-2 text-sm font-medium text-gray-700">No</th>
                    <th class="px-4 py-2 text-sm font-medium text-gray-700">Lecturer Name</th>
                    <th class="px-4 py-2 text-sm font-medium text-gray-700">Email</th>
                    <th class="px-4 py-2 text-sm font-medium text-gray-700">Program</th>
                    <th class="px-4 py-2 text-sm font-medium text-gray-700 text-center">Total Quota</th>
                    <th class="px-4 py-2 text-sm font-medium text-gray-700 text-center">Available Quota</th>
                </tr>
            </thead>
            <tbody>
                <!-- Rows will be updated dynamically -->
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div id="pagination-container" class="mt-6 flex justify-center space-x-2"></div>

    {{-- Print Preview Modal --}}
    <div id="report-modal" tabindex="-1" aria-hidden="true"
        class="hidden fixed inset-0 z-50 flex items-center justify-center bg-gray-900 bg-opacity-50">
        <div class="bg-white w-11/12 max-w-4xl rounded-lg shadow-lg">
            <!-- Modal Header -->
            <div class="flex justify-between items-center px-6 py-4 border-b">
                <h2 class="text-lg font-semibold">Lecturer Quota Report Preview</h2>
                <button id="close-modal" class="text-gray-500 hover:text-gray-700">&times;</button>
            </div>

            <!-- Modal Body -->
            <div class="p-6 overflow-y-auto max-h-[80vh]">
                <div id="report-content">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-xl font-bold">Lecturer Quota Report</h3>
                    </div>
                    <p id="report-semester" class="text-gray-600 mb-4">Semester: All Semesters</p>
                    <div class="grid grid-cols-3 gap-6 mb-6">
                        <div class="p-4 bg-white rounded shadow">
                            <p class="text-gray-600">Total Lecturers</p>
                            <h2 id="total-lecturers-modal" class="text-xl font-bold text-gray-800">0</h2>
                        </div>
                        <div class="p-4 bg-white rounded shadow">
                            <p class="text-gray-600">Total Quota</p>
                            <h2 id="total-quota-modal" class="text-xl font-bold text-gray-800">0</h2>
                        </div>
                        <div class="p-4 bg-white rounded shadow">
                            <p class="text-gray-600">Available Quota</p>
                            <h2 id="available-quota-modal" class="text-xl font-bold text-gray-800">0</h2>
                        </div>
                    </div>
                    <table class="w-full border-collapse border border-gray-300 mt-4">
                        <thead>
                            <tr class="bg-gray-100">
                                <th class="border px-4 py-2">No</th>
                                <th class="border px-4 py-2">Lecturer Name</th>
                                <th class="border px-4 py-2">Email</th>
                                <th class="border px-4 py-2">Program</th>
                                <th class="border px-4 py-2">Total Quota</th>
                                <th class="border px-4 py-2">Available Quota</th>
                            </tr>
                        </thead>
                        <tbody id="report-data">
                            <!-- Dynamic Rows will be added here -->
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Modal Footer -->
            <div class="flex justify-end px-6 py-4 border-t">
                <button id="print-report" class="text-white bg-blue-500 px-4 py-2 rounded hover:bg-blue-600">Print</button>
            </div>
        </div>
    </div>

    <!-- JavaScript -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const filterSemesterButton = document.getElementById('filter-semester-button');
            const tableBody = document.querySelector('#quota-table tbody');
            const paginationContainer = document.getElementById('pagination-container');
            const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

            // Print Report button click
            const generateReportBtn = document.getElementById('generate-report');
            const modal = document.getElementById('report-modal');
            const closeModalBtn = document.getElementById('close-modal');
            const printReportBtn = document.getElementById('print-report');
            const reportData = document.getElementById('report-data');

            const reportSemester = document.getElementById('report-semester');
            const totalLecturersModal = document.getElementById('total-lecturers-modal');
            const totalQuotaModal = document.getElementById('total-quota-modal');
            const availableQuotaModal = document.getElementById('available-quota-modal');

            let currentSemester = '';

            // Initial data load
            updateData();

            // Show a loading indicator
            function showLoading() {
                tableBody.innerHTML = `
                    <tr class="border-b">
                        <td colspan="6" class="px-4 py-2 text-center text-gray-500">Loading...</td>
                    </tr>
                `;
            }

            // Update the table with data
            function updateTable(quotas) {
                tableBody.innerHTML = '';
                if (quotas && quotas.length > 0) {
                    quotas.forEach((quota, index) => {
                        const row = `
                            <tr class="border-b">
                                <td class="px-4 py-2">${index + 1}</td>
                                <td class="px-4 py-2">${quota.lecturer.name || 'N/A'}</td>
                                <td class="px-4 py-2">${quota.lecturer.email || 'N/A'}</td>
                                <td class="px-4 py-2">
                                    ${
                                        quota.lecturer.program
                                            ? `${quota.lecturer.program.name}`
                                            : 'N/A'
                                    }
                                </td>
                                <td class="px-4 py-2 text-center">${quota.total_quota}</td>
                                <td class="px-4 py-2 text-center">${quota.remaining_quota}</td>
                            </tr>
                        `;
                        tableBody.innerHTML += row;
                    });
                } else {
                    tableBody.innerHTML = `
                        <tr>
                            <td colspan="6" class="px-4 py-2 text-center text-gray-500">No data available</td>
                        </tr>
                    `;
                }
            }

            // Update stats
            function updateStats(stats) {
                document.getElementById('total-lecturers').textContent = stats.totalLecturers || 0;
                document.getElementById('total-quota').textContent = stats.totalQuota || 0;
                document.getElementById('available-quota').textContent = stats.availableQuota || 0;
            }

            // Fetch and update data
            function updateData(semester = '') {
                showLoading();

                fetch('/admin/filter-by-semester', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken,
                        },
                        body: JSON.stringify({
                            semester
                        }),
                    })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error(`HTTP error! status: ${response.status}`);
                        }
                        return response.json();
                    })
                    .then(data => {
                        // Update Stats
                        updateStats(data.stats);

                        // Update Table
                        updateTable(data.quotas);
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        tableBody.innerHTML = `
                            <tr>
                                <td colspan="6" class="px-4 py-2 text-center text-red-500">An error occurred. Please try again.</td>
                            </tr>
                        `;
                    });
            }

            // Filter button click
            filterSemesterButton.addEventListener('click', function() {
                currentSemester = document.getElementById('semester').value;
                updateData(currentSemester);
            });

            // Open Modal and Populate Data
            generateReportBtn.addEventListener('click', async () => {
                try {
                    // Fetch all filtered data
                    const response = await fetch('/admin/filter-by-semester', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken,
                        },
                        body: JSON.stringify({
                            semester: currentSemester
                        }),
                    });

                    if (!response.ok) {
                        throw new Error('Network response was not ok.');
                    }

                    const data = await response.json();

                    // Update Modal Content
                    reportSemester.textContent = currentSemester ?
                        `Semester: ${currentSemester}` :
                        'Semester: All Semesters';

                    totalLecturersModal.textContent = data.stats.totalLecturers || 0;
                    totalQuotaModal.textContent = data.stats.totalQuota || 0;
                    availableQuotaModal.textContent = data.stats.availableQuota || 0;

                    // Populate Table Rows in Modal
                    reportData.innerHTML = data.quotas
                        .map((quota, index) => `
                            <tr>
                                <td class="border px-4 py-2 text-center">${index + 1}</td>
                                <td class="border px-4 py-2">${quota.lecturer.name || 'N/A'}</td>
                                <td class="border px-4 py-2">${quota.lecturer.email || 'N/A'}</td>
                                <td class="border px-4 py-2">
                                    ${
                                        quota.lecturer.program
                                            ? `${quota.lecturer.program.name}`
                                            : 'N/A'
                                    }
                                </td>
                                <td class="border px-4 py-2 text-center">${quota.total_quota}</td>
                                <td class="border px-4 py-2 text-center">${quota.remaining_quota}</td>
                            </tr>
                        `)
                        .join('');

                    // Show Modal
                    modal.classList.remove('hidden');
                } catch (error) {
                    console.error('Error generating report:', error);
                    alert('Failed to generate the report. Please try again.');
                }
            });

            // Close Modal
            closeModalBtn.addEventListener('click', () => {
                modal.classList.add('hidden');
            });

            // Print Report
            printReportBtn.addEventListener('click', () => {
                const content = document.getElementById('report-content').outerHTML;
                const now = new Date();
                const formattedTime = now.toLocaleString(); // Customize the format as needed

                // Base64 string of the logo (replace with your actual Base64 string or image URL)
                const logoBase64 =
                    `data:image/jpeg;base64,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`; // Replace with actual Base64
                // Open a new window
                const printWindow = window.open('', '_blank');
                printWindow.document.open();
                printWindow.document.write(`
                    <html>
                        <head>
                            <title>Lecturer Quota Report</title>
                            <style>
                                body { font-family: Arial, sans-serif; margin: 20px; }
                                .header {
                                    display: flex;
                                    justify-content: space-between;
                                    align-items: center;
                                    margin-bottom: 20px;
                                }
                                .generated-time {
                                    font-size: 0.9rem;
                                    color: #555;
                                    text-align: right;
                                }
                                table {
                                    width: 100%;
                                    border-collapse: collapse;
                                    margin-top: 20px;
                                }
                                th, td {
                                    border: 1px solid #ddd;
                                    padding: 8px;
                                    text-align: left;
                                }
                                th {
                                    background-color: #f4f4f4;
                                }
                                .grid {
                                    display: grid;
                                    grid-template-columns: repeat(3, 1fr);
                                    gap: 20px;
                                    margin-bottom: 20px;
                                }
                                .p-4 {
                                    padding: 16px;
                                }
                                .bg-white {
                                    background-color: #ffffff;
                                }
                                .rounded {
                                    border-radius: 8px;
                                }
                                .shadow {
                                    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
                                }
                                .text-gray-600 {
                                    color: #4b5563;
                                }
                                .text-gray-800 {
                                    color: #1f2937;
                                }
                                .text-xl {
                                    font-size: 1.25rem;
                                    font-weight: bold;
                                }
                                .font-bold {
                                    font-weight: bold;
                                }
                                .logo {
                                    width: 150px; /* Adjust the size as needed */
                                    height: auto;
                                }
                            </style>
                        </head>
                        <body>
                            <div class="header">
                                <img src="${logoBase64}" alt="Logo" class="logo">
                                <div class="generated-time">Generated at: ${formattedTime}</div>
                            </div>
                            ${content}
                        </body>
                    </html>
                `);
                printWindow.document.close();

                // Add a delay to allow browser to render content properly before print
                setTimeout(() => {
                    printWindow.print();
                    printWindow.close();
                }, 500);
            });
        });
    </script>
@endsection
