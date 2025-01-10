@extends('layouts.master')

@section('content')
<div class="container mt-5">
    <div class="card shadow-lg border-0">
        <div class="card-body">
            <h2 class="mb-4" style="color: #1814F3; font-weight: bold;">Request New Appointment</h2>
            <p class="text-muted">Complete the form to book an appointment with <strong>{{ $lecturer->name }}</strong>.</p>
            
            <form action="{{ route('appointments.store') }}" method="POST">
                @csrf

                <!-- Display the timetable -->
                @if (isset($timetable) && !empty($timetable->file_path))
                <div class="mb-4">
                    <h5 class="mb-3" style="color: #1814F3; font-weight: bold;">Lecturer Timetable</h5>
                    <div class="card border-0 shadow-sm" style="background-color: #f9f9ff;">
                        <div class="card-body d-flex align-items-center">
                            <div class="me-3">
                                <i class="bi bi-calendar-event" style="font-size: 2rem; color: #1814F3;"></i>
                            </div>
                            <div class="flex-grow-1">
                                <h6 class="mb-1" style="color: #1814F3; font-weight: bold;">{{ basename($timetable->file_path) }}</h6>
                                <p class="text-muted mb-0">Timetable file is available for viewing.</p>
                            </div>
                            <div>
                                <a href="{{ asset('storage/' . $timetable->file_path) }}" target="_blank" class="btn btn-outline-primary" style="color: #1814F3; font-weight: bold; border-radius: 20px; border: 2px solid #1814F3; padding: 10px 20px; text-decoration: none;"
                                   onmouseover="this.style.backgroundColor='#1814F3'; this.style.color='white';"
                                   onmouseout="this.style.backgroundColor='transparent'; this.style.color='#1814F3';">View Timetable</a>
                            </div>
                        </div>
                    </div>
                </div>
                @else
                <div class="alert alert-warning d-flex align-items-center" style="background-color: #fff7e6; border: 1px solid #ffcc99; border-radius: 10px; padding: 15px;">
                    <div class="me-3">
                        <i class="bi bi-exclamation-triangle-fill" style="font-size: 1.5rem; color: #ff9800;"></i>
                    </div>
                    <div>
                        <strong style="color: #1814F3; font-weight: bold;">Notice:</strong> No timetable is available for this lecturer.
                    </div>
                </div>
                @endif

                <!-- Appointment Form -->
                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <label for="appointment_date" class="form-label" style="color: #1814F3; font-weight: bold;">Date</label>
                        <input type="date" id="appointment_date" name="appointment_date" class="form-control shadow-sm" style="border-radius: 10px;" required>
                    </div>
                    <div class="col-md-6">
                        <label for="appointment_time" class="form-label" style="color: #1814F3; font-weight: bold;">Time</label>
                        <input type="time" id="appointment_time" name="appointment_time" class="form-control shadow-sm" style="border-radius: 10px;" required>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="lecturer_name" class="form-label" style="color: #1814F3; font-weight: bold;">Lecturer Name</label>
                    <input type="text" id="lecturer_name" name="lecturer_name" class="form-control bg-light shadow-sm" style="border-radius: 10px;" value="{{ $lecturer->name }}" readonly required>
                </div>

                <div class="mb-3">
                    <label for="appointment_type" class="form-label" style="color: #1814F3; font-weight: bold;">Reason</label>
                    <input type="text" id="appointment_type" name="appointment_type" class="form-control shadow-sm" style="border-radius: 10px;" placeholder="Enter the reason for the appointment" required>
                </div>

                <!-- Hidden Lecturer ID -->
                <input type="hidden" name="lecturer_id" value="{{ $lecturer->id }}">

                <!-- Submit and Back Buttons -->
                <div class="d-flex justify-content-between mt-4">
                    <button 
                        type="submit" 
                        class="btn" 
                        style="background-color: #1814F3; color: white; font-weight: bold; padding: 10px 20px; border-radius: 20px; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);"
                        onmouseover="this.style.backgroundColor='#0f0eca';"
                        onmouseout="this.style.backgroundColor='#1814F3';"
                    >
                        Save
                    </button>
                    
                    <a 
                        href="{{ route('search') }}" 
                        class="btn"
                        style="color: #1814F3; font-weight: bold; border: 2px solid #1814F3; padding: 10px 20px; border-radius: 20px; text-decoration: none; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);"
                        onmouseover="this.style.backgroundColor='#1814F3'; this.style.color='white';"
                        onmouseout="this.style.backgroundColor='transparent'; this.style.color='#1814F3';"
                    >
                        Back
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
