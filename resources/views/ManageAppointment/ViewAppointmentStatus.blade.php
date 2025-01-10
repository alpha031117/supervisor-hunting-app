@extends('layouts.master')

@section('content')
    <div class="container mx-auto my-10">
        @if (session('message'))
            <div class="alert alert-success alert-dismissible fade show text-center fs-5" role="alert">
                <i class="bi bi-check-circle me-2"></i> {{ session('message') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        <!-- Header Section -->
        <div class="text-center mb-10">
            <h1 class="text-4xl font-extrabold" style="color: #1814F3;">My Appointments</h1>
            <p class="text-lg text-gray-500 mt-2">Manage your appointments with ease and track your schedule effortlessly.
            </p>
        </div>

        <!-- Appointments Section -->
        @if ($appointments->isEmpty())
            <div class="bg-yellow-100 text-yellow-800 rounded-lg shadow-md p-6 text-center">
                <strong class="text-lg">No appointments found.</strong>
                <p class="mt-2 text-gray-600">Start by booking an appointment with your lecturer.</p>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach ($appointments as $appointment)
                    <div class="bg-white rounded-lg shadow-lg border border-gray-200 overflow-hidden">
                        <!-- Card Header -->
                        <div class="bg-gradient-to-r from-blue-900 to-blue-700 text-white p-4">
                            <h5 class="text-lg font-semibold flex items-center gap-2">
                                <i class="bi bi-person-circle"></i> {{ $appointment->lecturer->name }}
                            </h5>
                            <!-- Status Badge -->
                            @if ($appointment->status == 'Approved')
                                <span
                                    class="inline-block bg-green-500 text-white text-xs font-medium rounded-full px-2 py-1 mt-2">APPROVED</span>
                            @elseif ($appointment->status == 'Rejected')
                                <span
                                    class="inline-block bg-red-500 text-white text-xs font-medium rounded-full px-2 py-1 mt-2">REJECTED</span>
                            @else
                                <span
                                    class="inline-block bg-yellow-500 text-white text-xs font-medium rounded-full px-2 py-1 mt-2">PENDING</span>
                            @endif
                        </div>

                        <!-- Card Body -->
                        <div class="p-4">
                            <p class="text-gray-700 flex items-center gap-2">
                                <i class="bi bi-calendar-event text-blue-700"></i>
                                <strong>Date:</strong>
                                {{ \Carbon\Carbon::parse($appointment->appointment_date)->format('M d, Y') }}
                            </p>
                            <p class="text-gray-700 flex items-center gap-2 mt-2">
                                <i class="bi bi-clock text-blue-700"></i>
                                <strong>Time:</strong>
                                {{ \Carbon\Carbon::parse($appointment->appointment_time)->format('g:i A') }}
                            </p>
                            <!-- Progress Bar -->
                            <div class="relative pt-4">
                                <div class="h-2 bg-gray-200 rounded-full overflow-hidden">
                                    <div
                                        class="h-2 rounded-full transition-all duration-300 
                                    @if ($appointment->status == 'Pending') bg-blue-700 w-1/2
                                    @elseif ($appointment->status == 'Approved') bg-green-500 w-full
                                    @else bg-red-500 w-1/4 @endif">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Card Footer -->
                        <div class="bg-gray-50 p-4 flex justify-between items-center">
                            <button
                                class="bg-blue-700 text-white text-sm font-medium px-4 py-2 rounded-lg hover:bg-blue-800 transition"
                                onclick="location.href='{{ route('appointments.show', $appointment->id) }}';">
                                <i class="bi bi-eye"></i> View
                            </button>
                            <button
                                class="bg-red-500 text-white text-sm font-medium px-4 py-2 rounded-lg hover:bg-red-600 transition"
                                data-bs-toggle="modal" data-bs-target="#cancelModal{{ $appointment->id }}">
                                <i class="bi bi-trash"></i> Cancel
                            </button>
                        </div>
                    </div>

                    <!-- Cancel Modal -->
                    <div class="modal fade" id="cancelModal{{ $appointment->id }}" tabindex="-1"
                        aria-labelledby="cancelModalLabel{{ $appointment->id }}" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Cancel Appointment</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body">
                                    Are you sure you want to cancel your appointment with
                                    <strong>{{ $appointment->lecturer->name }}</strong> on
                                    <strong>{{ \Carbon\Carbon::parse($appointment->appointment_date)->format('M d, Y') }}</strong>
                                    at
                                    <strong>{{ \Carbon\Carbon::parse($appointment->appointment_time)->format('g:i A') }}</strong>?
                                </div>
                                <div class="modal-footer">
                                    <button type="button"
                                        class="bg-gray-300 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-400 transition"
                                        data-bs-dismiss="modal">Close</button>
                                    <form id="cancel-form-{{ $appointment->id }}"
                                        action="{{ route('appointments.cancel', $appointment->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="bg-red-500 text-white px-4 py-2 rounded-lg hover:bg-red-600 transition">Yes,
                                            Cancel</button>
                                    </form>
                                </div>
                            </div>
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
        document.addEventListener('DOMContentLoaded', () => {
            const alert = document.querySelector('.alert');
            if (alert) {
                setTimeout(() => {
                    successAlert.classList.remove('show');
                    alert.classList.add('fade');
                }, 3000); // 3 seconds
                // Remove alert from DOM after fading out
                setTimeout(() => {
                    alert.remove();
                }, 3500); //
            }
        });
    </script>
@endsection
