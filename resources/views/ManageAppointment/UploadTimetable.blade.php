@extends('layouts.master')

@section('content')
<div class="container mx-auto py-10">
    <!-- Welcome Section -->
    <div class="text-center mb-10">
        <h1 class="text-4xl font-extrabold" style="color: #1814F3;">Welcome, {{ auth()->user()->name }}!</h1>
        <p class="text-gray-600 text-lg">Ready to plan the upcoming term? Upload your timetable effortlessly below.</p>
    </div>

    <!-- Form Section -->
    <form id="uploadForm" action="{{ route('schedule.upload') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="max-w-4xl mx-auto">
            <div class="bg-white shadow-lg rounded-lg p-8">
                <!-- Creative Header -->
                <div class="text-center mb-6">
                    <h3 class="text-2xl font-semibold" style="color: #1814F3;">Upload Your Timetable</h3>
                    <p class="text-gray-500">Make sure it’s clear and accurate to help students schedule effectively.</p>
                </div>

                <!-- Name Display -->
                <div class="mb-6">
                    <label for="name" class="block text-lg font-bold mb-2" style="color: #1814F3;">Your Name</label>
                    <input type="text" id="name" 
                           class="w-full bg-blue-50 border-2 border-[#1814F3] rounded-lg shadow-sm px-4 py-3 text-[#1814F3] font-semibold" 
                           value="{{ auth()->user()->name }}" 
                           disabled>
                </div>

                <!-- Room Number Input -->
                <div class="mb-6">
                    <label for="room_no" class="block text-lg font-bold mb-2" style="color: #1814F3;">Room No</label>
                    <input type="text" id="room_no" name="room_no" 
                           class="w-full bg-blue-50 border-2 border-[#1814F3] rounded-lg shadow-sm px-4 py-3 text-[#1814F3] font-semibold @error('room_no') border-red-500 @enderror" 
                           value="{{ old('room_no', $timetable->room_no ?? '') }}" 
                           required>
                    @error('room_no')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Timetable Upload -->
                <div class="mb-6">
                    <label for="schedule" class="block text-lg font-bold mb-2" style="color: #1814F3;">Upload Your Timetable</label>

                    <!-- Current File Section -->
                    @if (isset($timetable) && $timetable->file_path)
                        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 flex items-center justify-between text-[#1814F3] shadow-sm">
                            <span>
                                <strong>Current File:</strong>
                                <a href="{{ asset('storage/' . $timetable->file_path) }}" 
                                   target="_blank" 
                                   class="underline font-bold hover:text-blue-700">
                                    {{ basename($timetable->file_path) }}
                                </a>
                            </span>
                        </div>
                    @endif

                    <!-- File Input -->
                    <div class="mt-4 relative">
                        <label class="flex flex-col items-center justify-center w-full h-20 bg-blue-50 border-2 border-dashed border-[#1814F3] rounded-lg cursor-pointer hover:bg-blue-100">
                            <span class="text-sm text-gray-600">Click to upload or drag and drop</span>
                            <span class="font-semibold text-gray-500">PDF, DOC, PNG (max: 2MB)</span>
                            <input type="file" id="schedule" name="schedule" class="hidden @error('schedule') border-red-500 @enderror">
                        </label>
                        @error('schedule')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- File Preview Section -->
                    <div id="filePreview" class="mt-4">
                        <!-- File name and optional preview will appear here -->
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="text-center mt-8">
                    <button type="submit" 
                            class="bg-gradient-to-r from-[#1814F3] to-[#4540E6] text-white font-bold px-10 py-3 rounded-full shadow-lg hover:scale-105 transition transform duration-300">
                        Submit
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>

<!-- Interactive Script -->
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const fileInput = document.getElementById('schedule');
        const filePreview = document.getElementById('filePreview');

        // File Input Change Event
        fileInput.addEventListener('change', function () {
            filePreview.innerHTML = ''; // Clear previous preview

            const file = fileInput.files[0];
            if (file) {
                // Display the file name
                const fileNameElement = document.createElement('p');
                fileNameElement.textContent = `Selected file: ${file.name}`;
                fileNameElement.classList.add('text-gray-700', 'font-semibold', 'mt-2');
                filePreview.appendChild(fileNameElement);

                // Preview for images
                if (file.type.startsWith('image/')) {
                    const imgPreview = document.createElement('img');
                    imgPreview.src = URL.createObjectURL(file);
                    imgPreview.style.maxWidth = '100%';
                    imgPreview.style.maxHeight = '200px';
                    imgPreview.classList.add('mt-4', 'border', 'rounded-lg');
                    filePreview.appendChild(imgPreview);
                }

                // Preview for text/PDF files
                if (file.type === 'application/pdf' || file.type.startsWith('text/')) {
                    const textPreview = document.createElement('embed');
                    textPreview.src = URL.createObjectURL(file);
                    textPreview.type = file.type;
                    textPreview.style.width = '100%';
                    textPreview.style.height = '200px';
                    textPreview.classList.add('mt-4', 'border', 'rounded-lg');
                    filePreview.appendChild(textPreview);
                }
            }
        });

        // Submit Button Hover Effect
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
