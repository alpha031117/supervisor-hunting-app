@extends('layouts.master')

@section('page', 'Lecturer Quota List')

@section('breadcrumbs')
    <a href="{{ route('coordinator.dashboard') }}" class="hover:text-blue-600">
        <li>FYP Hunt</li>
    </a>
    <li>/</li>
    <a href="{{ route('manage-lecturer-quota') }}" class="text-blue-600">
        <li>Manage Lecturer Quota</li>
    </a>
@endsection

@section('content')
    <div class="mt-10">
        <!-- Semester Filter and Generate Report Button -->
        <div class="flex items-center justify-between mb-6 mt-10">
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

            <!-- Right: Generate Report Button -->
            <a href="{{ route('lecturer-quota-report') }}"
                class="border border-blue-500 text-blue-500 px-4 py-2 rounded-lg bg-white hover:bg-blue-50">
                Generate Report
            </a>
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
                        <th scope="col" class="px-6 py-3 text-center">Semester</th>
                        <th scope="col" class="px-6 py-3 text-center">Total Quota</th>
                        <th scope="col" class="px-6 py-3 text-center">Action</th>
                    </tr>
                </thead>
                <tbody id="lecturer-quota-table-body">
                    @forelse($lecturers as $index => $lecturer)
                        <tr class="border-b h-16">
                            <td class="px-6 py-4 text-center">{{ $index + 1 }}</td>
                            <td class="px-6 py-4">{{ $lecturer->lecturer->name }}</td>
                            <td class="px-6 py-4">{{ $lecturer->lecturer->email }}</td>
                            <td class="px-6 py-4">{{ $lecturer->lecturer->program->name ?? 'N/A' }}</td>
                            <td class="px-6 py-4 text-center">{{ $lecturer->supervisorHuntingPeriod->semester ?? 'N/A' }}
                            </td>
                            <td class="px-6 py-4 text-center">{{ $lecturer->total_quota }}</td>
                            <td class="px-6 py-4 text-center">
                                <button
                                    onclick="openModal({{ $lecturer->lecturer_id }}, {{ $lecturer->total_quota }}, '{{ $lecturer->lecturer->name }}', '{{ $lecturer->supervisorHuntingPeriod->semester ?? '' }}')"
                                    class="min-w-[100px] px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    {{ $lecturer->total_quota == 0 ? 'Set Quota' : 'Edit Quota' }}
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr class="bg-transparent">
                            <td colspan="7" class="px-6 py-4 text-center text-gray-500">
                                No lecturer quota data available.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Modal -->
        <div id="quotaModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
            <!-- Background overlay -->
            <div class="fixed inset-0 bg-black bg-opacity-50" aria-hidden="true" onclick="closeModal()"></div>

            <!-- Modal container -->
            <div class="flex items-center justify-center min-h-screen p-4">
                <!-- Modal content -->
                <div class="relative w-full max-w-md p-6 bg-white rounded-lg shadow-xl">
                    <h3 class="text-lg font-medium leading-6 text-gray-900" id="modalTitle">
                        Set/Edit Quota
                    </h3>
                    <form id="quotaForm" method="POST">
                        @csrf
                        <div class="mt-4">
                            <label for="lecturer_name" class="block text-sm font-medium text-gray-700">Lecturer Name</label>
                            <input type="text" id="lecturer_name" name="lecturer_name"
                                class="w-full px-3 py-2 mt-1 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                readonly>
                        </div>
                        <div class="mt-4">
                            <label for="total_quota" class="block text-sm font-medium text-gray-700">Total Quota</label>
                            <input type="number" id="total_quota" name="total_quota"
                                class="w-full px-3 py-2 mt-1 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                required>
                        </div>
                        <div class="mt-6">
                            <button type="submit"
                                class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                Save Quota
                            </button>
                            <button type="button" onclick="closeModal()"
                                class="px-4 py-2 ml-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                Cancel
                            </button>
                        </div>
                    </form>
                </div>
            </div>
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
                                <td class="px-6 py-4 text-center">${lecturer.supervisor_hunting_period?.semester ?? 'N/A'}</td>
                                <td class="px-6 py-4 text-center">${lecturer.total_quota}</td>
                                <td class="px-6 py-4 text-center">
                                    <button
                                        onclick="openModal(${lecturer.lecturer_id}, ${lecturer.total_quota}, '${lecturer.lecturer.name}', '${lecturer.supervisor_hunting_period?.semester ?? ''}')"
                                        class="min-w-[100px] px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                        ${lecturer.total_quota == 0 ? 'Set Quota' : 'Edit Quota'}
                                    </button>
                                </td>
                            </tr>
                        `;
                            tableBody.innerHTML += row;
                        });

                        // If no data, show a message
                        if (data.lecturers.length === 0) {
                            tableBody.innerHTML = `
                            <tr class="border-b h-16">
                                <td colspan="7" class="px-6 py-4 text-center text-gray-500">
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

        // Function to open the modal
        function openModal(lecturerId, currentQuota, lecturerName, semester) {
            // Get the form element
            const quotaForm = document.getElementById('quotaForm');
            if (!quotaForm) {
                console.error('quotaForm not found in the DOM');
                return;
            }

            // Set the form action URL with the semester parameter
            quotaForm.action = `/save-lecturer-quota/${lecturerId}?semester=${semester}`;

            // Set the lecturer's name in the input field
            const lecturerNameInput = document.getElementById('lecturer_name');
            if (lecturerNameInput) {
                lecturerNameInput.value = lecturerName;
            } else {
                console.error('lecturer_name input not found in the DOM');
            }

            // Set the current quota value in the input field
            const totalQuotaInput = document.getElementById('total_quota');
            if (totalQuotaInput) {
                totalQuotaInput.value = currentQuota;
            } else {
                console.error('total_quota input not found in the DOM');
            }

            // Display the modal
            const quotaModal = document.getElementById('quotaModal');
            if (quotaModal) {
                quotaModal.classList.remove('hidden');
            } else {
                console.error('quotaModal not found in the DOM');
            }
        }

        // Function to close the modal
        function closeModal() {
            // Hide the modal
            const quotaModal = document.getElementById('quotaModal');
            if (quotaModal) {
                quotaModal.classList.add('hidden');
            } else {
                console.error('quotaModal not found in the DOM');
            }
        }

        // Handle form submission
        document.getElementById('quotaForm').addEventListener('submit', function(event) {
            event.preventDefault();

            // Submit the form using Fetch API
            fetch(this.action, {
                    method: 'POST',
                    body: new FormData(this),
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
                    },
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Close the modal
                        closeModal();

                        // Reload the page to reflect the changes
                        window.location.reload();
                    } else {
                        alert('Failed to save quota.');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                });
        });
    </script>
@endsection
