@extends('layouts.master')

@section('page', 'Set Timeframe')

@section('breadcrumbs')
    <li>FYP Hunt</li>
    <li>/</li>
    <li>Set Timeframe</li>
@endsection

@section('content')
    <form action="{{ route('store-timeframe') }}" method="POST" id="timeframe-form">
        @csrf
        <div class="mb-6 px-[21rem]">
            <label for="semester" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Semester</label>
            <input type="text" id="semester" name="semester" placeholder="e.g. Semester I 2024/25"
                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                value="{{ old('semester') }}">
            @error('semester')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>
        <div class="mt-10 flex justify-center items-center">
            <div class="grid grid-cols-3 gap-4 items-start"> <!-- Use items-start to align at the top -->
                <!-- Start Date -->
                <div>
                    <label for="start_date" class="block text-gray-700 font-medium mb-2 text-left">Start Date</label>
                    <div id="start-date-calendar" class="w-full"></div> <!-- Ensure full width -->
                    <input type="hidden" name="start_date" id="start_date_value" value="{{ old('start_date') }}">
                    @error('start_date')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Arrow -->
                <div class="flex justify-center mt-10"> <!-- Adjust margin-top to align with calendars -->
                    <x-heroicon-o-arrow-right class="w-8 h-8 text-gray-600" />
                </div>

                <!-- End Date -->
                <div class="flex flex-col">
                    <label for="end_date" class="block text-gray-700 font-medium mb-2 text-left">End Date</label>
                    <div id="end-date-calendar" class="w-full"></div> <!-- Ensure full width -->
                    <input type="hidden" name="end_date" id="end_date_value" value="{{ old('end_date') }}">
                    @error('end_date')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror

                    <!-- Save Button -->
                    <div class="flex justify-end mt-4">
                        <button type="submit"
                            class="px-4 py-2 bg-blue-600 text-white rounded-lg shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            Save Timeframe
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // Initialize Date Picker for start date (inline calendar)
            const startDateCalendar = document.getElementById('start-date-calendar');
            const startDateValue = document.getElementById('start_date_value');
            const startDatePicker = new Datepicker(startDateCalendar, {
                inline: true, // Display the calendar inline
                format: 'yyyy-mm-dd', // Format for the hidden input
                autohide: false, // Do not hide the calendar after selecting a date
                defaultDate: "{{ old('start_date') ?? '2024-01-01' }}", // Default date
            });

            // Update hidden input when a start date is selected
            startDateCalendar.addEventListener('changeDate', (event) => {
                const selectedDate = event.detail.date;
                // Format the date as YYYY-MM-DD without timezone conversion
                const formattedDate = selectedDate.toLocaleDateString('en-CA', {
                    year: 'numeric',
                    month: '2-digit',
                    day: '2-digit',
                });
                startDateValue.value = formattedDate;
                console.log('Start Date Selected:', startDateValue.value);
            });

            // Initialize Date Picker for end date (inline calendar)
            const endDateCalendar = document.getElementById('end-date-calendar');
            const endDateValue = document.getElementById('end_date_value');
            const endDatePicker = new Datepicker(endDateCalendar, {
                inline: true, // Display the calendar inline
                format: 'yyyy-mm-dd', // Format for the hidden input
                autohide: false, // Do not hide the calendar after selecting a date
                defaultDate: "{{ old('end_date') ?? '2024-01-25' }}", // Default date
            });

            // Update hidden input when an end date is selected
            endDateCalendar.addEventListener('changeDate', (event) => {
                const selectedDate = event.detail.date;
                // Format the date as YYYY-MM-DD without timezone conversion
                const formattedDate = selectedDate.toLocaleDateString('en-CA', {
                    year: 'numeric',
                    month: '2-digit',
                    day: '2-digit',
                });
                endDateValue.value = formattedDate;
                console.log('End Date Selected:', endDateValue.value);
            });

            // Ensure form submission includes the latest selected dates
            const form = document.getElementById('timeframe-form');
            form.addEventListener('submit', () => {
                const updatedStartDate = startDatePicker.getDate()?.toLocaleDateString('en-CA', {
                    year: 'numeric',
                    month: '2-digit',
                    day: '2-digit',
                });
                const updatedEndDate = endDatePicker.getDate()?.toLocaleDateString('en-CA', {
                    year: 'numeric',
                    month: '2-digit',
                    day: '2-digit',
                });

                startDateValue.value = updatedStartDate;
                endDateValue.value = updatedEndDate;

                console.log('Form Submitted - Start Date:', updatedStartDate);
                console.log('Form Submitted - End Date:', updatedEndDate);
            });
        });
    </script>
@endsection
