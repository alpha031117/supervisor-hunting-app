@extends('layouts.master')

@section('content')
<div class="container my-5">
    <!-- Header Section -->
    <div class="row mb-5 text-center">
        <div class="col-md-12">
            <h1 class="fw-bold display-5" style="color: #1814F3;">Appointment Details</h1>
            <p class="text-muted fs-5">Review your appointment information and take the necessary actions.</p>
        </div>
    </div>

    <!-- Appointment Details Section -->
    <div class="card shadow-lg border-0 rounded-3">
        <div class="card-header text-white fw-bold" style="background-color: #1814F3;">
           Appointment Information
        </div>
        <div class="card-body">
            <!-- Appointment Details -->
            <h4 class="fw-bold mb-3" style="color: #1814F3;">
                <i class="bi bi-person-circle me-2"></i> {{ $appointment->lecturer->name }}
            </h4>
            <ul class="list-unstyled fs-5">
                <li class="mb-3">
                    <i class="bi bi-calendar2-week me-2 text-primary"></i> 
                    <strong>Date:</strong> {{ $appointment->appointment_date }}
                </li>
                <li class="mb-3">
                    <i class="bi bi-clock me-2 text-primary"></i> 
                    <strong>Time:</strong> {{ \Carbon\Carbon::parse($appointment->appointment_time)->format('g:i A') }}
                </li>
                <li class="mb-3">
                    <i class="bi bi-flag me-2 text-primary"></i> 
                    <strong>Status:</strong> 
                    @if ($appointment->status == 'Pending')
                        <span class="badge rounded-pill px-3 py-2 bg-warning text-dark">Pending</span>
                    @elseif ($appointment->status == 'Approved')
                        <span class="badge rounded-pill px-3 py-2 bg-success">Approved</span>
                    @else
                        <span class="badge rounded-pill px-3 py-2 bg-danger">Rejected</span>
                    @endif
                </li>
                <li>
                    <i class="bi bi-chat-left-text me-2 text-primary"></i> 
                    <strong>Reason:</strong> {{ $appointment->reason }}
                </li>
            </ul>
        </div>
    </div>

    <!-- Actions -->
    <div class="mt-5 d-flex justify-content-between">
        <a href="{{ route('appointments.myAppointments') }}" 
           class="btn btn-primary-outline">
            <i class="bi bi-arrow-left"></i> Back to Appointments
        </a>
        
        @if ($appointment->status == 'Pending')
        <!-- Cancel Button to Trigger Modal -->
        <button type="button" 
                class="btn btn-outline-danger btn-lg px-4 rounded-pill shadow-sm" 
                data-bs-toggle="modal" 
                data-bs-target="#cancelModal">
            <i class="bi bi-x-circle"></i> Cancel Appointment
        </button>

        <!-- Cancel Modal -->
        <div class="modal fade" id="cancelModal" tabindex="-1" aria-labelledby="cancelModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="cancelModalLabel">Cancel Appointment</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        Are you sure you want to cancel your appointment with 
                        <strong>{{ $appointment->lecturer->name }}</strong> on 
                        <strong>{{ \Carbon\Carbon::parse($appointment->appointment_date)->format('M d, Y') }}</strong> 
                        at <strong>{{ \Carbon\Carbon::parse($appointment->appointment_time)->format('g:i A') }}</strong>?
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <form id="cancel-form" 
                              action="{{ route('appointments.cancel', $appointment->id) }}" 
                              method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">Yes, Cancel</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
