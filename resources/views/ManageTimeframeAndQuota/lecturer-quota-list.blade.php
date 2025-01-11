<!-- resources/views/ManageTimeframeAndQuota/lecturer-quota-list.blade.php -->

@extends('layouts.master')

@section('page', 'Lecturer Quota List')

@section('breadcrumbs')
    <a href="{{ route('coordinator.dashboard') }}" class="hover:text-blue-600">
        <li>FYP Hunt</li>
    </a>
    <li>/</li>
    <a href="{{ route('lecturer-quota-list') }}" class="text-blue-600">
        <li>Lecturer Quota List</li>
    </a>
@endsection

@section('content')
    <div class="mt-10">
        <!-- Semester Filter -->
        <div class="flex items-center justify-between mb-6">
            <!-- Left Group: Semester Filter & Filter Button -->
            <div class="flex items-center space-x-4">
                <select id="semester" class="border-b border-blue-500 text-blue-500 px-4 py-2 rounded-lg bg-white">
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
        </div>

        <!-- Table -->
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left text-gray-700 bg-transparent">
                <thead class="bg-gray-100">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-center">No</th>
                        <th scope="col" class="px-6 py-3">Name</th>
                        <th scope="col" class="px-6 py-3">Email</th>
                        <th scope="col" class="px-6 py-3">Program</th>
                        <th scope="col" class="px-6 py-3 text-center">Total Quota</th>
                    </tr>
                </thead>
                <tbody id="lecturer-quota-table-body">
                    @forelse($lecturers as $index => $lecturer)
                        <tr class="border-b h-16">
                            <td class="px-6 py-4 text-center">{{ $index + 1 }}</td>
                            <td class="px-6 py-4">{{ $lecturer->lecturer->name }}</td>
                            <td class="px-6 py-4">{{ $lecturer->lecturer->email }}</td>
                            <td class="px-6 py-4">
                                {{ $lecturer->lecturer->program->name ?? 'N/A' }}
                            </td>
                            <td class="px-6 py-4 text-center">{{ $lecturer->total_quota }}</td>
                        </tr>
                    @empty
                        <tr class="border-b h-16">
                            <td colspan="5" class="px-6 py-4 text-center text-gray-500">
                                No lecturer quota data available.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <script>
        // Function to handle semester filtering
        document.getElementById('filter-semester-button').addEventListener('click', function() {
            const semester = document.getElementById('semester').value;

            // Send AJAX request to filter lecturers by semester
            fetch(`/filter-lecturer-quota?semester=${semester}`, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                    },
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Clear the table body
                        const tableBody = document.getElementById('lecturer-quota-table-body');
                        tableBody.innerHTML = '';

                        // Populate the table with the filtered data
                        data.lecturers.forEach((lecturer, index) => {
                            const row = `
                            <tr class="border-b h-16">
                                <td class="px-6 py-4 text-center">${index + 1}</td>
                                <td class="px-6 py-4">${lecturer.lecturer.name}</td>
                                <td class="px-6 py-4">${lecturer.lecturer.email}</td>
                                <td class="px-6 py-4">${lecturer.lecturer.program?.name ?? 'N/A'}</td>
                                <td class="px-6 py-4 text-center">${lecturer.total_quota}</td>
                            </tr>
                        `;
                            tableBody.innerHTML += row;
                        });

                        // If no data, show a message
                        if (data.lecturers.length === 0) {
                            tableBody.innerHTML = `
                            <tr class="border-b h-16">
                                <td colspan="5" class="px-6 py-4 text-center text-gray-500">
                                    No lecturer quota data available.
                                </td>
                            </tr>
                        `;
                        }
                    } else {
                        alert('Failed to filter data.');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                });
        });
    </script>
@endsection
