@extends('layouts.master')

@section('page', 'Edit Timeframe')

@section('breadcrumbs')
    <a href="{{ route('lecturer.dashboard') }}" class="hover:text-blue-600">
        <li>FYP Hunt</li>
    </a>
    <li>/</li>
    <a href="{{ route('edit-timeframe') }}" class="text-blue-600">
        <li>Edit Timeframe</li>
    </a>
@endsection

@section('content')
    <div class="mt-10">
        <!-- Validation Error Section (Moved to the top) -->
        <div id="validation-error" class="hidden bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            <p id="validation-error-message"></p>
        </div>

        <!-- Success and Error Messages -->
        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4" role="alert">
                {{ session('success') }}
            </div>
        @endif
        @if (session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4" role="alert">
                {{ session('error') }}
            </div>
        @endif

        <!-- Semester Dropdown -->
        <div class="mb-6 px-[21rem]">
            <label for="semester" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Select
                Semester</label>
            <select id="semester" name="semester"
                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                <option value="">Choose a semester</option>
                @foreach ($timeframes as $timeframe)
                    <option value="{{ $timeframe->id }}"
                        {{ $currentPeriod && $timeframe->id == $currentPeriod->id ? 'selected' : '' }}>
                        {{ $timeframe->semester }}
                    </option>
                @endforeach
            </select>
        </div>

        <!-- Display Section for Old and New Dates -->
        <div class="mb-6 px-[21rem]">
            <div class="bg-gray-100 p-4 rounded-lg">
                <h3 class="text-lg font-medium mb-2">Date Information</h3>
                <div class="grid grid-cols-2 gap-4">
                    <!-- Old Dates -->
                    <div>
                        <p class="text-sm text-gray-600">Old Start Date:</p>
                        <p class="font-medium" id="old-start-date">
                            {{ $currentPeriod ? \Carbon\Carbon::parse($currentPeriod->start_date)->format('Y-m-d') : 'N/A' }}
                        </p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Old End Date:</p>
                        <p class="font-medium" id="old-end-date">
                            {{ $currentPeriod ? \Carbon\Carbon::parse($currentPeriod->end_date)->format('Y-m-d') : 'N/A' }}
                        </p>
                    </div>
                    <!-- New Dates -->
                    <div>
                        <p class="text-sm text-gray-600">New Start Date:</p>
                        <p class="font-medium" id="new-start-date">N/A</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">New End Date:</p>
                        <p class="font-medium" id="new-end-date">N/A</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Timeframe Form -->
        <form action="{{ route('update-timeframe') }}" method="POST" id="edit-timeframe-form">
            @csrf
            <input type="hidden" name="timeframe_id" id="timeframe_id" value="{{ $currentPeriod->id ?? '' }}">

            <div class="mt-10 flex justify-center items-center">
                <div class="grid grid-cols-3 gap-4 items-start"> <!-- Reduced gap from gap-1 to gap-4 -->
                    <!-- Start Date -->
                    <div> <!-- Reduced left margin from ml-20 to ml-10 -->
                        <label for="start_date" class="block text-gray-700 font-medium mb-2 text-left">Start Date</label>
                        <div id="start-date-calendar" inline-datepicker data-date="{{ $currentPeriod->start_date ?? '' }}">
                        </div>
                        <input type="hidden" name="start_date" id="start_date_value"
                            value="{{ $currentPeriod->start_date ?? '' }}">
                    </div>

                    <!-- Arrow -->
                    <div class="flex justify-center mt-44">
                        <x-heroicon-o-arrow-right class="w-8 h-8 text-gray-600" />
                    </div>

                    <!-- End Date -->
                    <div class="flex flex-col">
                        <div>
                            <label for="end_date" class="block text-gray-700 font-medium mb-2 text-left">End Date</label>
                            <div id="end-date-calendar" inline-datepicker data-date="{{ $currentPeriod->end_date ?? '' }}">
                            </div>
                            <input type="hidden" name="end_date" id="end_date_value"
                                value="{{ $currentPeriod->end_date ?? '' }}">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Update Button and Delete Button -->
            <div class="flex justify-end mt-8 space-x-4 mr-[338px]">
                <button type="button" id="delete-timeframe-button"
                    class="px-4 py-2 bg-red-600 text-white rounded-lg shadow-sm hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500">
                    Delete Timeframe
                </button>
                <button type="submit"
                    class="px-4 py-2 bg-blue-600 text-white rounded-lg shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    Update Timeframe
                </button>
            </div>
        </form>
    </div>

    <!-- Flowbite Date Picker Script -->
    <script>
        if (!window.datePickersInitialized) {
            document.addEventListener('DOMContentLoaded', () => {
                const validationError = document.getElementById('validation-error');
                const validationErrorMessage = document.getElementById('validation-error-message');

                // Function to validate dates
                const validateDates = (startDate, endDate) => {
                    if (startDate && endDate && startDate > endDate) {
                        validationError.classList.remove('hidden');
                        validationErrorMessage.textContent = 'End date must be after the start date.';
                        return false; // Invalid
                    } else {
                        validationError.classList.add('hidden');
                        validationErrorMessage.textContent = '';
                        return true; // Valid
                    }
                };

                // Initialize Date Picker for start date
                const startDateCalendar = document.getElementById('start-date-calendar');
                const startDateValue = document.getElementById('start_date_value');
                const newStartDateDisplay = document.getElementById('new-start-date');
                const startDatePicker = new Datepicker(startDateCalendar, {
                    inline: true,
                    format: 'yyyy-mm-dd', // Use the correct format
                    autohide: false,
                    defaultDate: "{{ $currentPeriod ? $currentPeriod->start_date : '' }}", // Set the default date
                });

                // Update hidden input and display section when a start date is selected
                startDateCalendar.addEventListener('changeDate', (event) => {
                    const selectedDate = event.detail.date;
                    const formattedDate = selectedDate.toISOString().split('T')[0];
                    startDateValue.value = formattedDate;
                    newStartDateDisplay.textContent = formattedDate; // Update display section

                    // Validate dates in real-time
                    const endDate = new Date(document.getElementById('end_date_value').value);
                    validateDates(new Date(formattedDate), endDate);
                });

                // Initialize Date Picker for end date
                const endDateCalendar = document.getElementById('end-date-calendar');
                const endDateValue = document.getElementById('end_date_value');
                const newEndDateDisplay = document.getElementById('new-end-date');
                const endDatePicker = new Datepicker(endDateCalendar, {
                    inline: true,
                    format: 'yyyy-mm-dd', // Use the correct format
                    autohide: false,
                    defaultDate: "{{ $currentPeriod ? $currentPeriod->end_date : '' }}", // Set the default date
                });

                // Update hidden input and display section when an end date is selected
                endDateCalendar.addEventListener('changeDate', (event) => {
                    const selectedDate = event.detail.date;
                    const formattedDate = selectedDate.toISOString().split('T')[0];
                    endDateValue.value = formattedDate;
                    newEndDateDisplay.textContent = formattedDate; // Update display section

                    // Validate dates in real-time
                    const startDate = new Date(document.getElementById('start_date_value').value);
                    validateDates(startDate, new Date(formattedDate));
                });

                // Handle semester dropdown change
                const semesterDropdown = document.getElementById('semester');
                semesterDropdown.addEventListener('change', () => {
                    const selectedSemesterId = semesterDropdown.value;
                    if (selectedSemesterId) {
                        // Redirect to the edit page for the selected semester
                        window.location.href = `/edit-timeframe/${selectedSemesterId}`;
                    }
                });

                // Handle Delete Button Click
                const deleteButton = document.getElementById('delete-timeframe-button');
                deleteButton.addEventListener('click', () => {
                    const confirmDelete = confirm(
                        'Are you sure you want to delete this timeframe? This action cannot be undone.');
                    if (confirmDelete) {
                        const timeframeId = document.getElementById('timeframe_id').value;
                        if (timeframeId) {
                            // Send a DELETE request to the server
                            fetch(`/delete-timeframe/${timeframeId}`, {
                                    method: 'DELETE',
                                    headers: {
                                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                        'Content-Type': 'application/json',
                                    },
                                })
                                .then(response => response.json())
                                .then(data => {
                                    if (data.success) {
                                        // Redirect to the timeframe list or show a success message
                                        window.location.href = '{{ route('edit-timeframe') }}';
                                    } else {
                                        alert('Failed to delete the timeframe.');
                                    }
                                })
                                .catch(error => {
                                    console.error('Error:', error);
                                    alert('An error occurred while deleting the timeframe.');
                                });
                        }
                    }
                });
            });
            window.datePickersInitialized = true;
        }

        console.log('Start Date:', "{{ $currentPeriod ? $currentPeriod->start_date : '' }}");
        console.log('End Date:', "{{ $currentPeriod ? $currentPeriod->end_date : '' }}");
    </script>
@endsection
