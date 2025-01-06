<!-- resources/views/ManageTimeframeAndQuota/lecturer-quota-list.blade.php -->

@extends('layouts.master')

@section('page', 'Lecturer Quota List')

@section('breadcrumbs')
    <li>FYP Hunt</li>
    <li>/</li>
    <li>Manage Timeframe and Quota</li>
    <li>/</li>
    <li>Lecturer Quota List</li>
@endsection

@section('content')
    <div class="mt-10">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left text-gray-700 bg-transparent">
                <thead>
                    <tr>
                        <th scope="col" class="px-6 py-3 text-center">No</th>
                        <th scope="col" class="px-6 py-3">Name</th>
                        <th scope="col" class="px-6 py-3">Email</th>
                        <th scope="col" class="px-6 py-3">Program</th>
                        <th scope="col" class="px-6 py-3 text-center">Total Quota</th>
                        <th scope="col" class="px-6 py-3 text-center">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($lecturers as $index => $lecturer)
                        <tr class="hover:bg-gray-100 bg-transparent">
                            <td class="px-6 py-4 text-center">{{ $index + 1 }}</td>
                            <td class="px-6 py-4">{{ $lecturer->lecturer->name }}</td>
                            <td class="px-6 py-4">{{ $lecturer->lecturer->email }}</td>
                            <td class="px-6 py-4">
                                {{ $lecturer->lecturer->program->name ?? 'N/A' }}
                            </td>
                            <td class="px-6 py-4 text-center">{{ $lecturer->total_quota }}</td>
                            <td class="px-6 py-4 text-center">
                                <button
                                    onclick="openModal({{ $lecturer->lecturer_id }}, {{ $lecturer->total_quota }}, '{{ $lecturer->lecturer->name }}')"
                                    class="min-w-[100px] px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    {{ $lecturer->total_quota == 0 ? 'Set Quota' : 'Edit Quota' }}
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr class="bg-transparent">
                            <td colspan="6" class="px-6 py-4 text-center text-gray-500">
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
        // Function to open the modal
        function openModal(lecturerId, currentQuota, lecturerName) {
            console.log('Opening modal for lecturer:', lecturerId); // Debugging

            // Set the form action URL
            document.getElementById('quotaForm').action = `/save-lecturer-quota/${lecturerId}`;

            // Set the lecturer's name in the input field
            document.getElementById('lecturer_name').value = lecturerName;

            // Set the current quota value in the input field
            document.getElementById('total_quota').value = currentQuota;

            // Display the modal
            document.getElementById('quotaModal').classList.remove('hidden');
        }

        // Function to close the modal
        function closeModal() {
            console.log('Closing modal'); // Debugging
            // Hide the modal
            document.getElementById('quotaModal').classList.add('hidden');
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
