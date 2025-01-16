@extends('layouts.master')

@section('content')
    <div class="container mx-auto my-10">
        <!-- Reminder Message -->
        @if (session('reminder'))
            <div id="reminderModal" class="fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center z-50">
                <div class="bg-white rounded-lg shadow-lg w-full max-w-md p-6 relative">
                    <!-- Header -->
                    <h3 class="text-xl font-bold text-blue-600 mb-4 flex items-center">
                        <i class="bi bi-info-circle-fill text-blue-600 mr-2"></i> Reminder
                    </h3>
                    <!-- Message -->
                    <p class="text-gray-600 text-center mb-6">{!! session('reminder') !!}</p>
                    <!-- Close Button -->
                    <br>
                    <div class="text-center">
                        <button onclick="closeReminderModal()"
                            class="bg-blue-600 text-white px-4 py-2 rounded-lg font-medium hover:bg-blue-800 transition">
                            Okay, got it!
                        </button>
                    </div>
                    <!-- Close Icon -->
                    <button onclick="closeReminderModal()" class="absolute top-2 right-2 text-gray-500 hover:text-gray-600">
                        <i class="bi bi-x-lg"></i>
                    </button>
                </div>
            </div>
        @endif
        <!-- Success Message -->
        @if (session('message'))
            <div
                class="bg-green-100 border border-green-300 text-green-600 px-4 py-3 rounded-lg text-center shadow-md mb-6">
                <i class="bi bi-check-circle-fill mr-2"></i>{{ session('message') }}
            </div>
        @endif

        <!-- Header Section -->
        <div class="text-center mb-10">
            <h1 class="text-4xl font-extrabold text-blue-600">My Appointments</h1>
            <p class="text-lg text-gray-600 mt-2">Manage your appointments with ease and track your schedule effortlessly.
            </p>
        </div>

        <!-- Appointments Section -->
        @if ($appointments->isEmpty())
            <div class="bg-yellow-100 border border-yellow-300 text-yellow-600 px-6 py-4 rounded-lg text-center shadow-md">
                <strong class="text-lg">No appointments found.</strong>
                <p class="mt-2 text-gray-500">Start by booking an appointment with your lecturer.</p>
            </div>
        @else
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach ($appointments as $appointment)
                    <div class="bg-white rounded-lg shadow-md border border-gray-200">
                        <!-- Card Header -->
                        <div class="p-4 bg-blue-600 text-white">
                            <h5 class="text-lg font-semibold flex items-center gap-2">
                                <i class="bi bi-person-circle"></i> {{ $appointment->lecturer->name }}
                            </h5>
                            <!-- Status Badge -->
                            <span
                                class="inline-block text-xs font-medium rounded-full px-2 py-1 mt-2
                            @if ($appointment->status == 'Approved') bg-green-500
                            @elseif ($appointment->status == 'Rejected') bg-red-500
                            @else bg-yellow-500 @endif">
                                {{ strtoupper($appointment->status) }}
                            </span>
                        </div>

                        <!-- Card Body -->
                        <div class="p-4">
                            <p class="text-gray-600 flex items-center gap-2">
                                <i class="bi bi-calendar-event text-blue-600"></i>
                                <strong>Date:</strong>
                                {{ \Carbon\Carbon::parse($appointment->appointment_date)->format('M d, Y') }}
                            </p>
                            <p class="text-gray-600 flex items-center gap-2 mt-2">
                                <i class="bi bi-clock text-blue-600"></i>
                                <strong>Time:</strong>
                                {{ \Carbon\Carbon::parse($appointment->appointment_time)->format('g:i A') }}
                            </p>

                            <!-- Progress Bar -->
                            <div class="relative pt-4">
                                <div class="h-2 bg-gray-200 rounded-full overflow-hidden">
                                    <div
                                        class="h-2 rounded-full transition-all duration-300
                                    @if ($appointment->status == 'Pending') bg-blue-600 w-1/2
                                    @elseif ($appointment->status == 'Approved') bg-green-500 w-full
                                    @else bg-red-500 w-1/4 @endif">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Card Footer -->
                        <div class="bg-gray-50 p-4 flex justify-between items-center">
                            <button
                                class="text-white text-sm font-medium px-4 py-2 rounded-lg hover:opacity-90 transition bg-blue-600"
                                onclick="location.href='{{ route('appointments.show', $appointment->id) }}';">
                                <i class="bi bi-eye"></i> View
                            </button>
                            <button
                                class="bg-red-500 text-white text-sm font-medium px-4 py-2 rounded-lg hover:bg-red-600 transition"
                                onclick="toggleModal('cancelModal{{ $appointment->id }}')">
                                <i class="bi bi-trash"></i> Cancel
                            </button>
                        </div>
                    </div>

                    <!-- Cancel Modal -->
                    <div id="cancelModal{{ $appointment->id }}"
                        class="fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center hidden z-50">
                        <div class="bg-white rounded-lg shadow-lg p-6 w-full max-w-md relative">
                            <h3 class="text-lg font-semibold mb-4">Cancel Appointment</h3>
                            <p class="text-gray-600 mb-6">
                                Are you sure you want to cancel your appointment with
                                <strong>{{ $appointment->lecturer->name }}</strong> on
                                <strong>{{ \Carbon\Carbon::parse($appointment->appointment_date)->format('M d, Y') }}</strong>
                                at
                                <strong>{{ \Carbon\Carbon::parse($appointment->appointment_time)->format('g:i A') }}</strong>?
                            </p>
                            <div class="flex justify-end gap-4">
                                <button class="bg-gray-300 text-gray-600 px-4 py-2 rounded-lg hover:bg-gray-400 transition"
                                    onclick="toggleModal('cancelModal{{ $appointment->id }}')">Close</button>
                                <form action="{{ route('appointments.cancel', $appointment->id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="bg-red-500 text-white px-4 py-2 rounded-lg hover:bg-red-600 transition">Yes,
                                        Cancel</button>
                                </form>
                            </div>
                            <button class="absolute top-2 right-2 text-gray-500 hover:text-gray-600"
                                onclick="toggleModal('cancelModal{{ $appointment->id }}')">
                                <i class="bi bi-x-lg"></i>
                            </button>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="flex justify-center mt-8">
                {{ $appointments->links() }}
            </div>
        @endif
    </div>

    <script>
        function toggleModal(modalId) {
            const modal = document.getElementById(modalId);
            modal.classList.toggle('hidden');
        }

        // function closeReminderModal() {
        //     const modal = document.getElementById('reminderModal');
        //     if (modal) {
        //         modal.style.display = 'none';
        //     }
        // }
        function closeReminderModal() {
            const modal = document.getElementById('reminderModal');
            if (modal) {
                modal.remove();
            }
        }
    </script>
@endsection
