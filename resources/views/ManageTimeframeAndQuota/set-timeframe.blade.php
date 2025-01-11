@extends('layouts.master')

@section('page', 'Set Timeframe')

@section('breadcrumbs')
    <a href="{{ route('coordinator.dashboard') }}" class="hover:text-blue-600">
        <li>FYP Hunt</li>
    </a>
    <li>/</li>
    <a href="{{ route('set-timeframe') }}" class="text-blue-600">
        <li>Set Timeframe</li>
    </a>
@endsection

@section('content')
    <!-- Server-side error messages (if any) will show up here if using standard session-based errors -->
    @if ($errors->any())
        <div class="mb-4">
            <ul class="list-disc list-inside text-red-600">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Front-end validation error container -->
    <div id="validation-error-container"></div>

    <form action="{{ route('store-timeframe') }}" method="POST" id="timeframe-form">
        @csrf
        <div class="mb-6 px-[21rem]">
            <label for="semester" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Semester</label>
            <input type="text" id="semester" name="semester" placeholder="e.g. Semester I 2024/25"
                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg
                       focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5
                       dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400
                       dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                value="{{ old('semester') }}">
            @error('semester')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="mt-10 flex justify-center items-center">
            <div class="grid grid-cols-3 gap-4 items-start">
                <!-- Start Date -->
                <div>
                    <label for="start_date" class="block text-gray-700 font-medium mb-2 text-left">Start Date</label>
                    <div id="start-date-calendar" class="w-full"></div>
                    <input type="hidden" name="start_date" id="start_date_value" value="{{ old('start_date') }}">
                    @error('start_date')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Arrow -->
                <div class="flex justify-center mt-44">
                    <x-heroicon-o-arrow-right class="w-8 h-8 text-gray-600" />
                </div>

                <!-- End Date -->
                <div class="flex flex-col">
                    <label for="end_date" class="block text-gray-700 font-medium mb-2 text-left">End Date</label>
                    <div id="end-date-calendar" class="w-full"></div>
                    <input type="hidden" name="end_date" id="end_date_value" value="{{ old('end_date') }}">
                    @error('end_date')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror

                    <!-- Save Button -->
                    <div class="flex justify-end mt-4">
                        <button type="submit" id="save-timeframe-button"
                            class="px-4 py-2 bg-blue-600 text-white rounded-lg shadow-sm hover:bg-blue-700
                                   focus:outline-none focus:ring-2 focus:ring-blue-500">
                            Save Timeframe
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <script>
        // Helper functions for front-end validation error
        function showValidationError(message) {
            const container = document.getElementById('validation-error-container');
            container.innerHTML = `
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4" role="alert">
                    ${message}
                </div>
            `;
        }

        function hideValidationError() {
            document.getElementById('validation-error-container').innerHTML = '';
        }

        document.addEventListener('DOMContentLoaded', () => {
            // Initialize Date Picker for start date (inline calendar)
            const startDateCalendar = document.getElementById('start-date-calendar');
            const startDateValue = document.getElementById('start_date_value');
            const startDatePicker = new Datepicker(startDateCalendar, {
                inline: true,
                format: 'yyyy-mm-dd',
                autohide: false,
                defaultDate: "{{ old('start_date') ?? '2024-01-01' }}",
            });

            // Initialize Date Picker for end date (inline calendar)
            const endDateCalendar = document.getElementById('end-date-calendar');
            const endDateValue = document.getElementById('end_date_value');
            const endDatePicker = new Datepicker(endDateCalendar, {
                inline: true,
                format: 'yyyy-mm-dd',
                autohide: false,
                defaultDate: "{{ old('end_date') ?? '2024-01-25' }}",
            });

            // Check and update date values on selection
            function handleDateChange() {
                const selectedStart = startDatePicker.getDate();
                const selectedEnd = endDatePicker.getDate();

                // Convert both to YYYY-MM-DD
                if (selectedStart) {
                    startDateValue.value = selectedStart.toLocaleDateString('en-CA', {
                        year: 'numeric',
                        month: '2-digit',
                        day: '2-digit',
                    });
                }
                if (selectedEnd) {
                    endDateValue.value = selectedEnd.toLocaleDateString('en-CA', {
                        year: 'numeric',
                        month: '2-digit',
                        day: '2-digit',
                    });
                }

                // If both dates are selected, check validity
                if (selectedStart && selectedEnd && selectedEnd < selectedStart) {
                    showValidationError("The end date cannot be before the start date.");
                } else {
                    hideValidationError();
                }
            }

            // Attach listeners
            startDateCalendar.addEventListener('changeDate', handleDateChange);
            endDateCalendar.addEventListener('changeDate', handleDateChange);

            // Final check on form submission
            const form = document.getElementById('timeframe-form');
            form.addEventListener('submit', (e) => {
                const selectedStart = startDatePicker.getDate();
                const selectedEnd = endDatePicker.getDate();

                // Update hidden fields in case user hasn't triggered 'changeDate'
                if (selectedStart) {
                    startDateValue.value = selectedStart.toLocaleDateString('en-CA', {
                        year: 'numeric',
                        month: '2-digit',
                        day: '2-digit',
                    });
                }
                if (selectedEnd) {
                    endDateValue.value = selectedEnd.toLocaleDateString('en-CA', {
                        year: 'numeric',
                        month: '2-digit',
                        day: '2-digit',
                    });
                }

                // If end < start, prevent submission
                if (selectedStart && selectedEnd && selectedEnd < selectedStart) {
                    e.preventDefault();
                    showValidationError("The end date cannot be before the start date.");
                }
            });
        });
    </script>
@endsection
