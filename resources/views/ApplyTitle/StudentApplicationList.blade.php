@extends('layouts.master')

@section('page', 'Student Application List')

@section('breadcrumbs')
    <a href="{{ route('lecturer.dashboard') }}" class="hover:text-blue-600">
        <li>FYP Hunt</li>
    </a>
    <li>/</li>
    <a href="/ApplicationList" class="text-blue-600">
        <li>Student Application List</li>
    </a>
@endsection

@section('content')
    <div class="container">
        @if (session('success'))
            <div class="bg-blue-100 border border-blue-400 text-blue-700 px-4 py-3 rounded relative mb-4" role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif

           <!-- My Post Proposal Button -->
           <div class="mb-4 flex justify-end">
            <a href="/postproposal"
                class="px-8 py-2 text-white bg-blue-700 shadow-lg hover:bg-blue-800 rounded-lg transition duration-300">
                Post Proposal
            </a>
        </div>


        <!-- Tabs -->
        <div>
            <ul class="flex border-b" id="tabs">
                <li class="mr-1">
                    <a href="?tab=pending"
                        class="inline-block px-4 py-2 border-b-2 {{ request('tab') === 'pending' || !request('tab') ? 'text-blue-600 border-blue-600' : 'text-gray-600 hover:text-blue-600' }}">
                        Pending
                    </a>
                </li>
                <li class="mr-1">
                    <a href="?tab=accepted"
                        class="inline-block px-4 py-2 border-b-2 {{ request('tab') === 'accepted' ? 'text-blue-600 border-blue-600' : 'text-gray-600 hover:text-blue-600' }}">
                        Accepted
                    </a>
                </li>
                <li class="mr-1">
                    <a href="?tab=rejected"
                        class="inline-block px-4 py-2 border-b-2 {{ request('tab') === 'rejected' ? 'text-blue-600 border-blue-600' : 'text-gray-600 hover:text-blue-600' }}">
                        Rejected
                    </a>
                </li>
            </ul>

            <!-- Tab Content -->
            <div class="tab-content mt-4">
                @if (request('tab') === 'pending' || !request('tab'))
                    <h3 class="text-lg font-bold mb-4">Pending Applications</h3>
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm text-left text-gray-700 bg-transparent">
                            <thead class="bg-gray-100">
                                <tr>
                                    <th class="px-6 py-3 text-center">No</th>
                                    <th class="px-6 py-3">Student</th>
                                    <th class="px-6 py-3">Proposed Title</th>
                                    <th class="px-6 py-3 text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($pendingApplications as $index => $application)
                                    @php
                                        $isPendingForMoreThan7Days = $pendingMoreThan7Days->contains($application->id);
                                    @endphp
                                    <tr class="border-b h-16">
                                        <td class="px-6 py-4 text-center">
                                            @if($isPendingForMoreThan7Days)
                                                <span class="text-red-600 text-lg">&#33;</span> <!-- Exclamation Icon -->
                                            @else
                                                {{ $index + 1 }}
                                            @endif
                                        </td>
                                        <td class="px-6 py-4">{{ $application->student->name }}</td>
                                        <td class="px-6 py-4">{{ $application->student_title }}</td>
                                        <td class="px-6 py-4 text-center">
                                            <a href="/application/{{ $application->id }}"
                                                class="text-blue-500 underline hover:text-blue-700">
                                                View
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @elseif (request('tab') === 'accepted')
                    <h3 class="text-lg font-bold mb-4">Accepted Applications</h3>
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm text-left text-gray-700 bg-transparent">
                            <thead class="bg-gray-100">
                                <tr>
                                    <th class="px-6 py-3 text-center">No</th>
                                    <th class="px-6 py-3">Student</th>
                                    <th class="px-6 py-3">Proposed Title</th>
                                    <th class="px-6 py-3 text-center">Remarks</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($acceptedApplications as $index => $application)
                                    <tr class="border-b h-16">
                                        <td class="px-6 py-4 text-center">{{ $index + 1 }}</td>
                                        <td class="px-6 py-4">{{ $application->student->name }}</td>
                                        <td class="px-6 py-4">{{ $application->student_title }}</td>
                                        <td class="px-6 py-4 text-center">{{ $application->remarks }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @elseif (request('tab') === 'rejected')
                    <h3 class="text-lg font-bold mb-4">Rejected Applications</h3>
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm text-left text-gray-700 bg-transparent">
                            <thead class="bg-gray-100">
                                <tr>
                                    <th class="px-6 py-3 text-center">No</th>
                                    <th class="px-6 py-3">Student</th>
                                    <th class="px-6 py-3">Proposed Title</th>
                                    <th class="px-6 py-3 text-center">Remarks</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($rejectedApplications as $index => $application)
                                    <tr class="border-b h-16">
                                        <td class="px-6 py-4 text-center">{{ $index + 1 }}</td>
                                        <td class="px-6 py-4">{{ $application->student->name }}</td>
                                        <td class="px-6 py-4">{{ $application->student_title }}</td>
                                        <td class="px-6 py-4 text-center">{{ $application->remarks }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
