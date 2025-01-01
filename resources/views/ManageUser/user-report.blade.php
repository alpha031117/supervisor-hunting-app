@extends('layouts.master')

@section('page', 'Manage User')

@section('breadcrumbs')
    <a href="{{ route('home')}}" class="hover:text-blue-600"><li>FYP Hunt</li></a>
    <li>/</li>
    <a href="{{ route('admin.user-list')}}" class="hover:text-blue-600"><li>Manage User</li></a>
    <li>/</li>
    <a href="{{ route('admin.user-report')}}"><li class="text-blue-600">User Report</li></a>
@endsection

@section('content')
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-semibold">User Report</h1>
    </div>

    <!-- Filters Section -->
    <div class="flex items-center space-x-4 mb-6">
        <select id="program" class="border border-blue-500 text-blue-500 px-4 py-2 rounded-lg bg-white hover:bg-blue-50">
            <option value="">All Programs</option>
            <option value="BCS">BCS Software Engineering</option>
            <option value="BCN">BCN Networking</option>
            <option value="BCE">BCE Embedded Systems</option>
        </select>
        <select id="year" class="border border-blue-500 text-blue-500 px-4 py-2 rounded-lg bg-white hover:bg-blue-50">
            <option value="">All Years</option>
            <option value="2024">2024</option>
            <option value="2023">2023</option>
            <option value="2022">2022</option>
            <option value="2021">2021</option>
        </select>
        <button id="filter-button" class="text-white bg-blue-700 hover:bg-blue-800 px-4 py-2 rounded-lg">Confirm</button>
    </div>

    <!-- Stats Section -->
    <div class="grid grid-cols-3 gap-6 mb-6">
        <div class="p-4 bg-white rounded shadow">
            <p class="text-gray-600">Total User Registered</p>
            <h2 id="total-users" class="text-xl font-bold text-gray-800">0</h2>
        </div>
        <div class="p-4 bg-white rounded shadow">
            <p class="text-gray-600">Number of Students</p>
            <h2 id="total-students" class="text-xl font-bold text-gray-800">0</h2>
        </div>
        <div class="p-4 bg-white rounded shadow">
            <p class="text-gray-600">Number of Lecturers</p>
            <h2 id="total-lecturers" class="text-xl font-bold text-gray-800">0</h2>
        </div>
    </div>

    <!-- Generate Report -->
    <div class="flex justify-end mb-6">
        <a href="#" id="generate-report" class="border border-blue-500 text-blue-500 px-4 py-2 rounded-lg bg-white hover:bg-blue-50">
            Generate Report
        </a>
    </div>

    <!-- Table -->
    <div class="overflow-x-auto">
        <table id="user-table" class="table-auto w-full text-left">
            <thead class="bg-gray-100">
                <tr>
                    <th class="px-4 py-2 text-sm font-medium text-gray-700">No</th>
                    <th class="px-4 py-2 text-sm font-medium text-gray-700">Name</th>
                    <th class="px-4 py-2 text-sm font-medium text-gray-700">Email</th>
                    <th class="px-4 py-2 text-sm font-medium text-gray-700">Program</th>
                    <th class="px-4 py-2 text-sm font-medium text-gray-700 text-center">Role</th>
                </tr>
            </thead>
            <tbody>
                <!-- Rows will be updated dynamically -->
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div id="pagination-container" class="mt-6 flex justify-center space-x-2"></div>

    <!-- JavaScript -->
    <script>
document.addEventListener('DOMContentLoaded', function () {
    const filterButton = document.getElementById('filter-button');
    const tableBody = document.querySelector('#user-table tbody');
    const paginationContainer = document.getElementById('pagination-container');
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;

    let currentProgram = '';
    let currentYear = '';

    // Show a loading indicator
    function showLoading() {
        tableBody.innerHTML = `
            <tr>
                <td colspan="5" class="px-4 py-2 text-center text-gray-500">Loading...</td>
            </tr>
        `;
    }

    // Update the table with data
    function updateTable(users, page, perPage) {
        tableBody.innerHTML = '';
        if (users && users.length > 0) {
            users.forEach((user, index) => {
                const row = `
                    <tr>
                        <td class="px-4 py-2">${(page - 1) * perPage + index + 1}</td>
                        <td class="px-4 py-2">${user.name || 'N/A'}</td>
                        <td class="px-4 py-2">${user.email || 'N/A'}</td>
                        <td class="px-4 py-2">${user.program || 'N/A'}</td>
                        <td class="px-4 py-2 text-center">${user.role || 'N/A'}</td>
                    </tr>
                `;
                tableBody.innerHTML += row;
            });
        } else {
            tableBody.innerHTML = `
                <tr>
                    <td colspan="5" class="px-4 py-2 text-center text-gray-500">No data available</td>
                </tr>
            `;
        }
    }

    // Update pagination buttons
    function updatePagination(currentPage, lastPage) {
        paginationContainer.innerHTML = '';
        for (let i = 1; i <= lastPage; i++) {
            const pageButton = document.createElement('button');
            pageButton.textContent = i;
            pageButton.className = `px-3 py-1 mx-1 rounded ${
                i === currentPage ? 'bg-blue-500 text-white' : 'bg-gray-200 text-gray-700'
            }`;
            pageButton.addEventListener('click', () => updateData(currentProgram, currentYear, i));
            paginationContainer.appendChild(pageButton);
        }
    }

    // Fetch and update data
    function updateData(program = '', year = '', page = 1) {
        showLoading();

        fetch(`/admin/filter-data?page=${page}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
            },
            body: JSON.stringify({ program, year }),
        })
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                console.log('Received Data:', data);

                // Update Stats Cards
                document.getElementById('total-users').textContent = data.cards?.totalUsers || 0;
                document.getElementById('total-students').textContent = data.cards?.totalStudents || 0;
                document.getElementById('total-lecturers').textContent = data.cards?.totalLecturers || 0;

                // Update Table and Pagination
                updateTable(data.users, data.pagination.current_page, data.pagination.per_page);
                updatePagination(data.pagination.current_page, data.pagination.last_page);
            })
            .catch(error => {
                console.error('Error:', error);
                tableBody.innerHTML = `
                    <tr>
                        <td colspan="5" class="px-4 py-2 text-center text-red-500">An error occurred. Please try again.</td>
                    </tr>
                `;
            });
    }

    // Initial data load
    updateData();

    // Filter button click
    filterButton.addEventListener('click', function () {
        currentProgram = document.getElementById('program')?.value || '';
        currentYear = document.getElementById('year')?.value || '';
        updateData(currentProgram, currentYear);
    });
});


    </script>
@endsection
