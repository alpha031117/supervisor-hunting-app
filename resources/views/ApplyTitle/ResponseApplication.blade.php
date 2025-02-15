@extends('layouts.master')

@section('page', 'Response Application')

@section('breadcrumbs')
    <a href="{{ route('lecturer.dashboard') }}" class="hover:text-blue-600">
        <li>FYP Hunt</li>
    </a>
    <li>/</li>
    <a href="/ApplicationList" class="hover:text-blue-600">
        <li>Student Application List</li>
    </a>
    <li>/</li>
    <a href="/application/{{ $application->id }}" class="text-blue-600">
        <li>Response Application</li>
    </a>
@endsection

@section('content')
    <div class="container mx-auto px-4 py-6">
        <div class="bg-white p-6 rounded-lg shadow-lg max-w-2xl mx-auto">

            <h2 class="text-2xl font-semibold text-gray-800 mb-4">Student Application - {{ $application->student->name }}</h2>

            @if (session('success'))
                <div class="bg-blue-100 border border-blue-400 text-blue-700 px-4 py-3 rounded relative mb-6" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            <!-- Application Title and Description -->
            <div class="mb-6">
                <label for="project-title" class="block text-lg font-medium text-gray-700 mb-2">Project Title</label>
                <input type="text" id="project-title" class="w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500" value="{{ $application->student_title }}" readonly>
            </div>

            <div class="mb-6">
                <label for="project-description" class="block text-lg font-medium text-gray-700 mb-2">Project Description</label>
                <textarea id="project-description" class="w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500" rows="5" readonly>{{ $application->student_description }}</textarea>
            </div>

            <!-- Response Form -->
            <form method="POST" action="/submitresponse/{{ $application->id }}">
                @csrf
                @method('PUT')

                <div class="mb-6">
                    <label for="remarks" class="block text-lg font-medium text-gray-700 mb-2">Remarks</label>
                    <textarea id="remarks" name="remarks" class="w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500" rows="3" placeholder="Add your remarks here..."></textarea>
                </div>

                <div class="flex justify-start gap-4">
                    <button type="submit" name="status" value="accepted" class="px-6 py-2 text-white bg-green-600 hover:bg-green-700 rounded-lg shadow-md transition duration-200">
                        Accept
                    </button>
                    <button type="submit" name="status" value="rejected" class="px-6 py-2 text-white bg-red-600 hover:bg-red-700 rounded-lg shadow-md transition duration-200">
                        Reject
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
