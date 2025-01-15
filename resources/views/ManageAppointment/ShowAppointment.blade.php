@extends('layouts.master')

@section('content')
<div class="container mx-auto my-10">
    <!-- Header Section -->
    <div class="text-center mb-10">
        <h1 class="text-4xl font-extrabold text-blue-600">Appointment Details</h1>
        <p class="text-gray-600 text-lg">Review your appointment information and take the necessary actions.</p>
    </div>

    <!-- Appointment Details Section -->
    <div class="bg-white shadow-lg rounded-lg border border-gray-200">
        <div class="bg-blue-600 text-white font-bold px-6 py-4 rounded-t-lg">
            Appointment Information
        </div>
        <div class="p-6">
            <!-- Appointment Details -->
            <h4 class="text-xl font-bold mb-5 flex items-center gap-2 text-blue-600">
                <i class="bi bi-person-circle"></i> {{ $appointment->lecturer->name }}
            </h4>
            <ul class="space-y-4 text-lg">
                <!-- Reason -->
                <li class="flex items-center">
                    <i class="bi bi-chat-left-text text-blue-600 mr-3"></i>
                    <strong>Reason:</strong> <span class="ml-2">{{ $appointment->reason }}</span>
                </li>

                <!-- Date -->
                <li class="flex items-center">
                    <i class="bi bi-calendar2-week text-blue-600 mr-3"></i>
                    <strong>Date:</strong> <span class="ml-2">{{ $appointment->appointment_date }}</span>
                </li>

                <!-- Time -->
                <li class="flex items-center">
                    <i class="bi bi-clock text-blue-600 mr-3"></i>
                    <strong>Time:</strong> <span class="ml-2">{{ \Carbon\Carbon::parse($appointment->appointment_time)->format('g:i A') }}</span>
                </li>

                <!-- Room No -->
                <li class="flex items-center">
                    <i class="bi bi-door-open text-blue-600 mr-3"></i>
                    <strong>Room No:</strong> <span class="ml-2">{{ $appointment->lecturer->timetable->room_no ?? 'N/A' }}</span>
                </li>

                <!-- Status -->
                <li class="flex items-center">
                    <i class="bi bi-flag text-blue-600 mr-3"></i>
                    <strong>Status:</strong>
                    <span class="ml-2">
                        @if ($appointment->status == 'Pending')
                            <span class="bg-yellow-200 text-yellow-600 px-3 py-1 rounded-full text-sm font-medium">Pending</span>
                        @elseif ($appointment->status == 'Approved')
                            <span class="bg-green-200 text-green-600 px-3 py-1 rounded-full text-sm font-medium">Approved</span>
                        @else
                            <span class="bg-red-200 text-red-600 px-3 py-1 rounded-full text-sm font-medium">Rejected</span>
                        @endif
                    </span>
                </li>
            </ul>
        </div>
    </div>

    <!-- Actions -->
    <div class="mt-8 flex justify-between">
        <a href="{{ route('appointments.myAppointments') }}"
           class="text-blue-600 border border-blue-600 px-6 py-2 rounded-full hover:bg-blue-600 hover:text-white transition">
            <i class="bi bi-arrow-left"></i> Back to Appointments
        </a>

        @if ($appointment->status == 'Pending')
        <!-- Cancel Button to Trigger Modal -->
        <button type="button"
                class="bg-red-500 text-white px-6 py-2 rounded-full hover:bg-red-600 transition shadow-md"
                data-modal-target="#cancelModal">
            <i class="bi bi-x-circle"></i> Cancel Appointment
        </button>

        <!-- Cancel Modal -->
        <div id="cancelModal" class="fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center hidden">
            <div class="bg-white rounded-lg shadow-lg p-6 w-full max-w-md">
                <h5 class="text-lg font-semibold mb-4">Cancel Appointment</h5>
                <p class="text-gray-600 mb-6">
                    Are you sure you want to cancel your appointment with
                    <strong>{{ $appointment->lecturer->name }}</strong> on
                    <strong>{{ \Carbon\Carbon::parse($appointment->appointment_date)->format('M d, Y') }}</strong>
                    at <strong>{{ \Carbon\Carbon::parse($appointment->appointment_time)->format('g:i A') }}</strong>?
                </p>
                <div class="flex justify-end gap-4">
                    <button
                        class="bg-gray-300 text-gray-600 px-4 py-2 rounded-lg hover:bg-gray-400 transition"
                        data-modal-close="#cancelModal">Close</button>
                    <form id="cancel-form"
                          action="{{ route('appointments.cancel', $appointment->id) }}"
                          method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded-lg hover:bg-red-600 transition">
                            Yes, Cancel
                        </button>
                    </form>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>

<script>
    // Modal handling
    document.querySelectorAll("[data-modal-target]").forEach(button => {
        button.addEventListener("click", () => {
            const target = button.getAttribute("data-modal-target");
            document.querySelector(target).classList.remove("hidden");
        });
    });

    document.querySelectorAll("[data-modal-close]").forEach(button => {
        button.addEventListener("click", () => {
            const target = button.getAttribute("data-modal-close");
            document.querySelector(target).classList.add("hidden");
        });
    });
</script>
@endsection
