@extends('layouts.master')

@section('content')
<div class="container mx-auto mt-10">
    <div class="bg-white shadow-lg rounded-lg">
        <div class="p-6">
            <h2 class="text-2xl font-bold text-blue-600 mb-4">Request New Appointment</h2>
            <p class="text-gray-600">Complete the form to book an appointment with <strong>{{ $lecturer->name }}</strong>.</p>

            <form id="appointmentForm" action="{{ route('appointments.store') }}" method="POST" class="mt-6">
                @csrf

                <!-- Display the timetable -->
                @if (isset($timetable) && !empty($timetable->file_path))
                <div class="mb-6">
                    <h5 class="text-lg font-bold text-blue-600 mb-3">Lecturer Timetable</h5>
                    <div class="bg-blue-50 p-4 rounded-lg shadow-sm flex items-center">
                        <div class="mr-4">
                            <i class="bi bi-calendar-event text-3xl text-blue-600"></i>
                        </div>
                        <div class="flex-grow">
                            <h6 class="text-blue-600 font-bold">{{ basename($timetable->file_path) }}</h6>
                            <p class="text-gray-500">Timetable file is available for viewing.</p>
                        </div>
                        <div>
                            <a href="{{ asset('storage/' . $timetable->file_path) }}" target="_blank"
                                class="inline-block bg-transparent text-blue-600 border border-blue-600 px-5 py-2 rounded-full hover:bg-blue-600 hover:text-white transition">
                                View Timetable
                            </a>
                        </div>
                    </div>
                </div>
                @else
                <div class="bg-yellow-100 text-yellow-600 border border-yellow-300 rounded-lg p-4 flex items-center mb-6">
                    <div class="mr-4">
                        <i class="bi bi-exclamation-triangle-fill text-2xl"></i>
                    </div>
                    <div>
                        <strong class="font-bold text-blue-600">Notice:</strong> No timetable is available for this lecturer.
                    </div>
                </div>
                @endif

                <div class="mb-4">
                    <label for="room_no" class="block text-blue-600 font-bold mb-1">Room No</label>
                    <input type="text" id="room_no" name="room_no"
                           class="w-full bg-gray-100 rounded-lg shadow-sm border-gray-300 focus:ring-blue-500 focus:border-blue-500"
                           value="{{ $timetable->room_no ?? 'N/A' }}" readonly>
                </div>

                <!-- Appointment Form -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-6">
                    <div>
                        <label for="appointment_date" class="block text-blue-600 font-bold mb-1">Date</label>
                        <input type="date" id="appointment_date" name="appointment_date"
                               class="w-full rounded-lg shadow-sm border-gray-300 focus:ring-blue-500 focus:border-blue-500" required>
                    </div>
                    <div>
                        <label for="appointment_time" class="block text-blue-600 font-bold mb-1">Time</label>
                        <input type="time" id="appointment_time" name="appointment_time"
                               class="w-full rounded-lg shadow-sm border-gray-300 focus:ring-blue-500 focus:border-blue-500" required>
                    </div>
                </div>

                <div class="mb-4">
                    <label for="lecturer_name" class="block text-blue-600 font-bold mb-1">Lecturer Name</label>
                    <input type="text" id="lecturer_name" name="lecturer_name"
                           class="w-full bg-gray-100 rounded-lg shadow-sm border-gray-300 focus:ring-blue-500 focus:border-blue-500"
                           value="{{ $lecturer->name }}" readonly required>
                </div>

                <div class="mb-4">
                    <label for="appointment_type" class="block text-blue-600 font-bold mb-1">Reason</label>
                    <input type="text" id="appointment_type" name="appointment_type"
                           class="w-full rounded-lg shadow-sm border-gray-300 focus:ring-blue-500 focus:border-blue-500"
                           placeholder="Enter the reason for the appointment" required>
                </div>

                <!-- Hidden Lecturer ID -->
                <input type="hidden" name="lecturer_id" value="{{ $lecturer->id }}">

                <!-- Submit and Back Buttons -->
                <div class="flex justify-between items-center mt-6">
                    <button type="submit"
                            class="bg-blue-600 text-white px-6 py-3 rounded-full shadow-lg hover:bg-blue-800 transition">
                        Save
                    </button>

                    <a href="{{ route('search') }}"
                       class="text-blue-600 border border-blue-600 px-6 py-3 rounded-full hover:bg-blue-600 hover:text-white transition">
                        Back
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    // Restrict date to today and later
    const today = new Date();
    const yyyy = today.getFullYear();
    const mm = String(today.getMonth() + 1).padStart(2, '0');
    const dd = String(today.getDate()).padStart(2, '0');

    const minDate = `${yyyy}-${mm}-${dd}`;
    document.getElementById('appointment_date').setAttribute('min', minDate);

    // Confirmation alert on submit
    document.getElementById('appointmentForm').addEventListener('submit', function(e) {
        e.preventDefault(); // Prevent default form submission

        // Show confirmation alert
        alert('You have booked the appointment successfully.');

        // Submit the form
        this.submit();
    });
</script>
@endsection
