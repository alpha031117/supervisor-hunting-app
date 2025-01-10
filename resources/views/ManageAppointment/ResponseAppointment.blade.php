@extends('layouts.master')

@section('content')
<div class="container mx-auto my-10">
    <!-- Header Section -->
    <div class="text-center mb-10">
        <h1 class="text-4xl font-bold" style="color: #1814F3;">Pending Appointment Requests</h1>
        <p class="text-gray-500 text-lg">Review and manage appointment requests effortlessly.</p>
    </div>

    <!-- Pending Requests Section -->
    <div class="bg-white shadow-md rounded-lg overflow-hidden mb-10">
        <div class="rounded-t-lg bg-blue-900" style="background-color: #1814F3;">
            <h5 class="text-white text-lg font-bold px-6 py-4 flex items-center">
                <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-3-3v6m-6 4h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
                Pending Appointment Requests
            </h5>
        </div>
        <div class="p-6">
            @if ($pendingAppointments->isNotEmpty())
                <div class="overflow-x-auto">
                    <table class="w-full border-collapse border border-gray-200">
                        <thead>
                            <tr class="bg-gray-50 text-gray-700 text-left text-sm uppercase">
                                <th class="px-6 py-3">Student Name</th>
                                <th class="px-6 py-3">Date</th>
                                <th class="px-6 py-3">Time</th>
                                <th class="px-6 py-3">Reason</th>
                                <th class="px-6 py-3">Status</th>
                                <th class="px-6 py-3 text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="text-gray-700">
                            @foreach ($pendingAppointments as $appointment)
                                <tr class="hover:bg-gray-100 transition">
                                    <td class="px-6 py-3 font-medium">{{ $appointment->student->name }}</td>
                                    <td class="px-6 py-3">{{ $appointment->appointment_date }}</td>
                                    <td class="px-6 py-3">{{ \Carbon\Carbon::parse($appointment->appointment_time)->format('g:i A') }}</td>
                                    <td class="px-6 py-3">{{ $appointment->reason }}</td>
                                    <td class="px-6 py-3">
                                        <span class="inline-block bg-yellow-400 text-black text-xs font-medium px-3 py-1 rounded-full">Pending</span>
                                    </td>
                                    <td class="px-6 py-3 flex justify-center gap-2">
                                        <form action="{{ route('approveAppointment', $appointment->id) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="bg-green-500 hover:bg-green-600 text-white text-sm font-semibold px-4 py-2 rounded-lg transition">
                                                Approve
                                            </button>
                                        </form>
                                        <form action="{{ route('rejectAppointment', $appointment->id) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="bg-red-500 hover:bg-red-600 text-white text-sm font-semibold px-4 py-2 rounded-lg transition">
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
                    <i class="bi bi-calendar-x text-4xl text-gray-400"></i>
                    <p class="mt-3 text-gray-500">No pending appointments at the moment.</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Upcoming Appointments Section -->
    <div class="text-center mb-6">
        <h2 class="text-3xl font-bold" style="color: #1814F3;">Upcoming Appointments</h2>
        <p class="text-gray-500">Keep track of your upcoming approved appointments.</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse ($approvedAppointments as $appointment)
            <div class="bg-white shadow-lg rounded-lg hover:shadow-xl transition overflow-hidden">
                <div class="bg-blue-900 text-white px-6 py-4" style="background-color: #1814F3;">
                    <h5 class="text-lg font-semibold">{{ $appointment->student->name }}</h5>
                </div>
                <div class="p-6">
                    <p class="text-gray-700 flex items-center gap-2">
                        <i class="bi bi-calendar2-week text-blue-800"></i>
                        <strong>Date:</strong> {{ $appointment->appointment_date }}
                    </p>
                    <p class="text-gray-700 flex items-center gap-2 mt-2">
                        <i class="bi bi-clock text-blue-800"></i>
                        <strong>Time:</strong> {{ \Carbon\Carbon::parse($appointment->appointment_time)->format('g:i A') }}
                    </p>
                    <p class="text-gray-700 flex items-center gap-2 mt-2">
                        <i class="bi bi-info-circle text-blue-800"></i>
                        <strong>Reason:</strong> {{ $appointment->reason }}
                    </p>
                    <p class="text-green-600 flex items-center gap-2 mt-4">
                        <i class="bi bi-check-circle-fill"></i>
                        <strong>Status:</strong> Approved
                    </p>
                </div>
                <div class="bg-gray-50 px-6 py-4 text-sm text-gray-500">
                    Approved on {{ $appointment->updated_at->format('d M Y, g:i A') }}
                </div>
            </div>
        @empty
            <div class="col-span-full text-center">
                <div class="bg-blue-50 text-blue-800 p-6 rounded-lg shadow-md">
                    <p>No upcoming appointments available.</p>
                </div>
            </div>
        @endforelse
    </div>

    <!-- Bottom Spacer -->
    <div class="h-12"></div>
</div>
@endsection
