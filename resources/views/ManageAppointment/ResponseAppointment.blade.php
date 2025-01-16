@extends('layouts.master')

@section('content')

@if (session('reminder'))
<div id="reminderModal" class="fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center z-50">
    <div class="bg-white rounded-lg shadow-lg w-full max-w-md relative">
        <!-- Header - Fixed at top -->
        <div class="p-6 border-b">
            <h3 class="text-xl font-bold text-blue-600 flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-600 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                </svg>
                Reminder
            </h3>
        </div>

        <!-- Scrollable Content -->
        <div class="p-6 max-h-96 overflow-y-auto">
            <p class="text-gray-600">{!! session('reminder') !!}</p>
        </div>

        <!-- Footer - Fixed at bottom -->
        <div class="p-6 border-t bg-white">
            <div class="text-center">
                <button onclick="closeReminderModal()"
                    class="bg-blue-600 text-white px-4 py-2 rounded-lg font-medium hover:bg-blue-700 transition">
                    Okay, got it!
                </button>
            </div>
        </div>

        <!-- Close Icon -->
        <button onclick="closeReminderModal()" class="absolute top-2 right-2 text-gray-500 hover:text-gray-600">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
    </div>
</div>
@endif

    <!-- Header Section -->
    <div class="text-center mb-10">
        <h1 class="text-4xl font-bold text-blue-600">Pending Appointment Requests</h1>
        <p class="text-gray-500 text-lg">Review and manage appointment requests effortlessly.</p>
    </div>

    <!-- Pending Requests Section -->
    <div class="bg-white shadow-md rounded-lg overflow-hidden mb-10">
        <div class="rounded-t-lg bg-blue-600">
            <h5 class="text-white text-lg font-bold px-6 py-4 flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M9 12h6m-3-3v6m-6 4h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                Pending Appointment Requests
            </h5>
        </div>
        <div class="p-6">
            @if ($pendingAppointments->isNotEmpty())
                <div class="overflow-x-auto">
                    <table class="w-full border-collapse border border-gray-200">
                        <thead>
                            <tr class="bg-gray-50 text-gray-600 text-left text-sm uppercase">
                                <th class="px-6 py-3">Student Name</th>
                                <th class="px-6 py-3">Date</th>
                                <th class="px-6 py-3">Time</th>
                                <th class="px-6 py-3">Reason</th>
                                <th class="px-6 py-3">Status</th>
                                <th class="px-6 py-3 text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="text-gray-600">
                            @foreach ($pendingAppointments as $appointment)
                                <tr class="hover:bg-gray-100 transition">
                                    <td class="px-6 py-3 font-medium">{{ $appointment->student->name }}</td>
                                    <td class="px-6 py-3">{{ $appointment->appointment_date }}</td>
                                    <td class="px-6 py-3">
                                        {{ \Carbon\Carbon::parse($appointment->appointment_time)->format('g:i A') }}</td>
                                    <td class="px-6 py-3">{{ $appointment->reason }}</td>
                                    <td class="px-6 py-3">
                                        <span
                                            class="inline-block bg-yellow-400 text-black text-xs font-medium px-3 py-1 rounded-full">Pending</span>
                                    </td>
                                    <td class="px-6 py-3 flex justify-center gap-2">
                                        <form action="{{ route('approveAppointment', $appointment->id) }}" method="POST">
                                            @csrf
                                            <button type="submit"
                                                class="bg-green-500 hover:bg-green-600 text-white text-sm font-semibold px-4 py-2 rounded-lg transition flex items-center gap-1">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                                    viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M5 13l4 4L19 7" />
                                                </svg>
                                                Approve
                                            </button>
                                        </form>
                                        <form action="{{ route('rejectAppointment', $appointment->id) }}" method="POST">
                                            @csrf
                                            <button type="submit"
                                                class="bg-red-500 hover:bg-red-600 text-white text-sm font-semibold px-4 py-2 rounded-lg transition flex items-center gap-1">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                                    viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M6 18L18 6M6 6l12 12" />
                                                </svg>
                                                Reject
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-10">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-gray-400 mx-auto" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <p class="mt-3 text-gray-500">No pending appointments at the moment.</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Upcoming Appointments Section -->
    <div class="text-center mb-6">
        <h2 class="text-3xl font-bold text-blue-600">Upcoming Appointments</h2>
        <p class="text-gray-500">Keep track of your upcoming approved appointments.</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse ($approvedAppointments as $appointment)
            <div class="bg-white shadow-lg rounded-lg hover:shadow-xl transition overflow-hidden">
                <div class="bg-blue-600 text-white px-6 py-4">
                    <h5 class="text-lg font-semibold">{{ $appointment->student->name }}</h5>
                </div>
                <div class="p-6">
                    <p class="text-gray-600 flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-600" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M8 7H16M8 11H16M8 15H16M5 6H19V18H5V6Z" />
                        </svg>
                        <strong>Date:</strong> {{ $appointment->appointment_date }}
                    </p>
                    <p class="text-gray-600 flex items-center gap-2 mt-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-600" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M12 8v4l3 3m7-3A9 9 0 1112 3a9 9 0 0110 10z" />
                        </svg>
                        <strong>Time:</strong> {{ \Carbon\Carbon::parse($appointment->appointment_time)->format('g:i A') }}
                    </p>
                    <p class="text-gray-600 flex items-center gap-2 mt-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-600" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M13 16h-1v-4h-1m1 4h.01M12 9h.01M12 17h.01M12 3a9 9 0 100 18 9 9 0 000-18z" />
                        </svg>
                        <strong>Reason:</strong> {{ $appointment->reason }}
                    </p>
                    <p class="text-gray-600 flex items-center gap-2 mt-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                        </svg>
                        <strong>Status:</strong> Approved
                    </p>
                </div>
                <div class="bg-gray-50 px-6 py-4 text-sm text-gray-500">
                    Approved on {{ $appointment->updated_at->format('d M Y, g:i A') }}
                </div>
            </div>
        @empty
            <div class="col-span-full text-center">
                <div class="bg-blue-50 text-blue-600 p-6 rounded-lg shadow-md">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto mb-2" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <p>No upcoming appointments available.</p>
                </div>
            </div>
        @endforelse
    </div>

    <!-- Bottom Spacer -->
    <div class="h-12"></div>

    <script>
        function toggleModal(modalId) {
            const modal = document.getElementById(modalId);
            modal.classList.toggle('hidden');
        }

        function closeReminderModal() {
            const modal = document.getElementById('reminderModal');
            if (modal) {
                modal.remove();
            }
        }
    </script>
@endsection
