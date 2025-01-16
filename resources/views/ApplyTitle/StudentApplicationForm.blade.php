@extends('layouts.master')

@section('page', 'Student Application Form')

@section('breadcrumbs')
    <a href="{{ route('student.dashboard') }}" class="hover:text-blue-600">
        <li>FYP Hunt</li>
    </a>
    <li>/</li>
    <a href="/ProposalList" class="hover:text-blue-600">
        <li>Proposal List</li>
    </a>
    <li>/</li>
    <a href="/createapplication/{{ $lecturer->id }}" class="text-blue-600">
        <li>Student Application Form</li>
    </a>
@endsection

@section('content')
<div class="container mx-auto px-4 py-8">
    {{-- <h2 class="text-xl font-semibold mb-6">Student Application with {{ $lecturer->name }}</h2> --}}

    <!-- Application Form -->
    <form action="/submitapplication" method="POST" class="bg-white shadow-md rounded-lg p-8">
        @csrf
        <input type="hidden" name="lecturer_id" value="{{ $lecturer->id }}">

        <div class="mb-6">
            <label for="project_title" class="block text-sm font-medium text-gray-700 mb-2">Project Title</label>
            <input type="text" class="block w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500" id="project_title" name="project_title" placeholder="Enter your project title" required>
        </div>

        <div class="mb-6">
            <label for="project_description" class="block text-sm font-medium text-gray-700 mb-2">Project Description</label>
            <textarea class="block w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500" id="project_description" name="project_description" rows="5" placeholder="Enter project description" required></textarea>
        </div>

        <div class="flex justify-end">
            <button type="submit" class="px-8 py-2 text-white bg-blue-700 shadow-lg hover:bg-blue-800 rounded-lg transition duration-300">
                Submit
            </button>
        </div>
    </form>
</div>
@endsection
