@extends('layouts.master')

@section('page', 'Proposal Details')

@section('breadcrumbs')
    <a href="{{ route('student.dashboard') }}" class="hover:text-blue-600">
        <li>FYP Hunt</li>
    </a>
    <li>/</li>
    <a href="/ProposalList" class="hover:text-blue-600">
        <li>Proposal List</li>
    </a>
    <li>/</li>
    <a href="/proposal/{{ $proposal->id }}" class="text-blue-600">
        <li>Proposal Details</li>
    </a>
@endsection

@section('content')
    <div class="container mx-auto px-4 py-6">
        <div class="bg-white p-6 rounded-lg shadow-lg max-h-[450px] overflow-y-auto">

            <!-- Proposal Title and Description -->
            <h2 class="text-2xl font-semibold text-gray-800">{{ $proposal->proposal_title }}</h2>
            <p class="text-gray-600 mt-2">{{ $proposal->proposal_description }}</p>

            <div class="mt-6">
                <!-- Lecturer Information -->
                <p class="text-gray-800 font-medium"><strong>Lecturer Name:</strong> {{ $proposal->lecturer->name ?? 'N/A' }}</p>
                <p class="text-gray-800 font-medium mt-2"><strong>Lecturer Quota:</strong>
                    @php
                        $quota = $proposal->lecturer->lecturerQuotas->first() ?? null;
                    @endphp
                    @if ($quota)
                        <span class="text-green-500">{{ $quota->remaining_quota }} / {{ $quota->total_quota }}</span>
                    @else
                        <span class="text-red-500">No Quota Assigned</span>
                    @endif
                </p>
                <p class="text-gray-800 font-medium mt-2"><strong>Proposal Status:</strong>{{ $proposal->status }}</p>
            </div>

            <!-- Action Buttons -->
            <div class="mt-6 space-x-4">
                <a href="/applyproposal/{{ $proposal->id }}" class="inline-block px-6 py-2 text-white bg-blue-600 hover:bg-blue-700 rounded-lg shadow-md transition duration-200">
                    Apply this title
                </a>
                <a href="/createapplication/{{ $proposal->lecturer->id }}" class="inline-block px-6 py-2 text-white bg-green-600 hover:bg-green-700 rounded-lg shadow-md transition duration-200">
                    Apply supervisor with own title
                </a>
            </div>
        </div>
    </div>
@endsection
