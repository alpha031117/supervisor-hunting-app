@extends('layouts.master')

@section('content')
    <div class="container py-5">
        <!-- Welcome Section -->
        <div class="row mb-5 text-center">
            <div class="col">
                <h1 class="fw-bold" style="color: #1814F3;">Welcome, {{ auth()->user()->name }}!</h1>
                <p class="text-muted fs-5">
                    Ready to plan the upcoming term? Upload your timetable effortlessly below.
                </p>
            </div>
        </div>

        <!-- Form Section -->
        <form action="{{ route('schedule.upload') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card shadow-lg border-0" 
                         style="border-radius: 15px; background: linear-gradient(135deg, #FFFFFF, #F4F4F9);">
                        <div class="card-body p-5">
                            <!-- Creative Header -->
                            <div class="text-center mb-4">
                                <h3 class="fw-semibold" style="color: #1814F3;">Upload Your Timetable</h3>
                                <p class="text-muted">Make sure it’s clear and accurate to help students schedule effectively.</p>
                            </div>

                            <!-- Name Display -->
                            <div class="mb-4">
                                <label for="name" class="form-label fw-bold" style="color: #1814F3;">Your Name</label>
                                <input type="text" id="name" 
                                       class="form-control shadow-sm" 
                                       value="{{ auth()->user()->name }}" 
                                       disabled 
                                       style="border: 2px solid #1814F3; border-radius: 10px; background-color: #F9F9FF; color: #1814F3; font-weight: 600;">
                            </div>

                            <!-- Timetable Upload -->
                            <div class="mb-4">
                                <label for="schedule" class="form-label fw-bold" style="color: #1814F3;">Upload Your Timetable</label>

                                <!-- Current File Section -->
                                @if (isset($timetable) && $timetable->file_path)
                                    <div class="alert alert-light border-1 shadow-sm d-flex align-items-center justify-content-between" 
                                         style="background: #F1F5FF; color: #1814F3;">
                                        <span>
                                            <strong>Current File:</strong>
                                            <a href="{{ asset('storage/' . $timetable->file_path) }}" 
                                               target="_blank" 
                                               class="text-decoration-none fw-bold" 
                                               style="color: #1814F3;">
                                                {{ basename($timetable->file_path) }}
                                            </a>
                                        </span>
                                    </div>
                                @endif

                                <!-- Hidden Input -->
                                <input type="hidden" id="uploaded_file" name="uploaded_file" 
                                       value="{{ isset($timetable) ? basename($timetable->file_path) : '' }}">

                                <!-- File Input -->
                                <div class="file-upload-area mt-3" style="position: relative;">
                                    <input type="file" id="schedule" name="schedule" 
                                           class="form-control shadow-sm @error('schedule') is-invalid @enderror" 
                                           style="border: 2px dashed #1814F3; border-radius: 10px; padding: 20px; background-color: #F9F9FF;">
                                    <small class="text-muted d-block mt-2">Accepted formats: PDF, DOC, PNG. Max size: 2MB.</small>
                                    @error('schedule')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Submit Button -->
                            <div class="d-flex justify-content-center mt-4">
                                <button type="submit" 
                                        class="btn text-white fw-bold px-5 py-3"
                                        style="
                                            background: linear-gradient(135deg, #1814F3, #4540E6);
                                            border-radius: 50px; 
                                            box-shadow: 0px 10px 20px rgba(24, 20, 243, 0.2); 
                                            transition: transform 0.3s ease;">
                                    Upload Timetable
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <!-- Interactive Script -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const uploadButton = document.querySelector('button[type="submit"]');
            uploadButton.addEventListener('mouseover', () => {
                uploadButton.style.transform = 'scale(1.05)';
            });
            uploadButton.addEventListener('mouseout', () => {
                uploadButton.style.transform = 'scale(1)';
            });
        });
    </script>
@endsection
