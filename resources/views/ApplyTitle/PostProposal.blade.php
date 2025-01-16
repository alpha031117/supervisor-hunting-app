@extends('layouts.master')

@section('page', 'Post Proposal Title')

@section('breadcrumbs')
    <a href="{{ route('lecturer.dashboard') }}" class="hover:text-blue-600">
        <li>FYP Hunt</li>
    </a>
    <li>/</li>
    <a href="/ApplicationList" class="hover:text-blue-600">
        <li>Student Application List</li>
    </a>
    <li>/</li>
    <a href="/postproposal" class="text-blue-600">
        <li>Post Proposal</li>
    </a>
@endsection

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Form to Submit a Proposal -->
    <form action="/submitproposal" method="POST" class="bg-white shadow-md rounded-lg p-8">
        @csrf

        <div class="mb-6">
            <label for="proposal_title" class="block text-sm font-medium text-gray-700 mb-2">Project Title</label>
            <input type="text" name="proposal_title" id="proposal_title"
                class="block w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                placeholder="Enter your project title" required>
        </div>

        <div class="mb-6">
            <label for="proposal_description" class="block text-sm font-medium text-gray-700 mb-2">Project Description</label>
            <textarea name="proposal_description" id="proposal_description" rows="5"
                class="block w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                placeholder="Provide a detailed description of your project" required></textarea>
        </div>

        <div class="flex justify-end">
            <button type="submit"
                class="px-8 py-2 text-white bg-blue-700 shadow-lg hover:bg-blue-800 rounded-lg transition duration-300">
                Submit
            </button>
        </div>
    </form>
</div>
@endsection
