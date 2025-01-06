@extends('layouts.master')

@section('page', 'Edit Timeframe')

@section('breadcrumbs')
    <li>FYP Hunt</li>
    <li>/</li>
    <li>Edit Timeframe</li>
@endsection

@section('content')
    <div class="mt-10">
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
            <label for="semester" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Select Semester</label>
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

        <!-- Timeframe Form -->
        <form action="{{ route('update-timeframe') }}" method="POST" id="edit-timeframe-form">
            @csrf
            <input type="hidden" name="timeframe_id" id="timeframe_id" value="{{ $currentPeriod->id ?? '' }}">

            <div class="mt-10 flex justify-center items-center">
                <div class="grid grid-cols-3 gap-4 items-start">
                    <!-- Start Date -->
                    <div>
                        <label for="start_date" class="block text-gray-700 font-medium mb-2 text-left">Start Date</label>
                        <div id="start-date-calendar" inline-datepicker
                            data-date="{{ $currentPeriod->formatted_start_date ?? '' }}"></div>
                        <input type="hidden" name="start_date" id="start_date_value"
                            value="{{ $currentPeriod->start_date ?? '' }}">
                    </div>
                    <!-- Arrow -->
                    <div class="flex justify-center mt-10">
                        <x-heroicon-o-arrow-right class="w-8 h-8 text-gray-600" />
                    </div>

                    <!-- End Date -->
                    <div class="flex flex-col">
                        <div>
                            <label for="end_date" class="block text-gray-700 font-medium mb-2 text-left">End Date</label>
                            <div id="end-date-calendar" inline-datepicker
                                data-date="{{ $currentPeriod->formatted_end_date ?? '' }}"></div>
                            <input type="hidden" name="end_date" id="end_date_value"
                                value="{{ $currentPeriod->end_date ?? '' }}">
                        </div>

                        <!-- Update Button -->
                        <div class="flex justify-end mt-4">
                            <button type="submit"
                                class="px-4 py-2 bg-blue-600 text-white rounded-lg shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                Update Timeframe
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <!-- Flowbite Date Picker Script -->
    <script>
        if (!window.datePickersInitialized) {
            document.addEventListener('DOMContentLoaded', () => {
                // Initialize Date Picker for start date
                const startDateCalendar = document.getElementById('start-date-calendar');
                const startDateValue = document.getElementById('start_date_value');
                const startDatePicker = new Datepicker(startDateCalendar, {
                    inline: true,
                    format: 'mm/dd/yyyy', // Use the correct format
                    autohide: false,
                    defaultDate: "{{ $currentPeriod->formatted_start_date ?? '' }}", // Set the default date
                });

                // Update hidden input when a start date is selected
                startDateCalendar.addEventListener('changeDate', (event) => {
                    const selectedDate = event.detail.date;
                    const formattedDate = selectedDate.toLocaleDateString('en-CA', {
                        year: 'numeric',
                        month: '2-digit',
                        day: '2-digit',
                    });
                    startDateValue.value = formattedDate;
                    console.log('Start Date Selected:', startDateValue.value);
                });

                // Initialize Date Picker for end date
                const endDateCalendar = document.getElementById('end-date-calendar');
                const endDateValue = document.getElementById('end_date_value');
                const endDatePicker = new Datepicker(endDateCalendar, {
                    inline: true,
                    format: 'mm/dd/yyyy', // Use the correct format
                    autohide: false,
                    defaultDate: "{{ $currentPeriod->formatted_end_date ?? '' }}", // Set the default date
                });

                // Update hidden input when an end date is selected
                endDateCalendar.addEventListener('changeDate', (event) => {
                    const selectedDate = event.detail.date;
                    const formattedDate = selectedDate.toLocaleDateString('en-CA', {
                        year: 'numeric',
                        month: '2-digit',
                        day: '2-digit',
                    });
                    endDateValue.value = formattedDate;
                    console.log('End Date Selected:', endDateValue.value);
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
            });
            window.datePickersInitialized = true;
        }

        console.log('Formatted Start Date:', "{{ $currentPeriod->formatted_start_date ?? '' }}");
        console.log('Formatted End Date:', "{{ $currentPeriod->formatted_end_date ?? '' }}");
    </script>
@endsection
