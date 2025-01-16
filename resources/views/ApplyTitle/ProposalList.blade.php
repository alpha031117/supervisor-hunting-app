@extends('layouts.master')

@section('page', 'Proposal List')

@section('breadcrumbs')
    <a href="{{ route('student.dashboard') }}" class="hover:text-blue-600">
        <li>FYP Hunt</li>
    </a>
    <li>/</li>
    <a href="/ProposalList" class="text-blue-600">
        <li>Proposal List</li>
    </a>
@endsection

@section('content')
    <div class="container">
        {{-- <h2 class="my-4 text-xl font-semibold">List of Proposals</h2> --}}

        @if (session('success'))
            <div class="bg-blue-100 border border-blue-400 text-blue-700 px-4 py-3 rounded relative mb-4" role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif

        <!-- Table -->
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left text-gray-700 bg-transparent border border-gray-200">
                <thead class="bg-gray-100">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-center">#</th>
                        <th scope="col" class="px-6 py-3">Lecturer Name</th>
                        <th scope="col" class="px-6 py-3">Lecturer Quota</th>
                        <th scope="col" class="px-6 py-3">Proposal Title</th>
                        <th scope="col" class="px-6 py-3 text-center">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($proposals as $index => $proposal)
                        <tr class="border-b h-16">
                            <td class="px-6 py-4 text-center">{{ $index + 1 }}</td>
                            <td class="px-6 py-4">{{ $proposal->lecturer->name ?? 'N/A' }}</td>
                            <td class="px-6 py-4">
                                @php
                                    $quota = $proposal->lecturer->lecturerQuotas->first() ?? null;
                                @endphp
                                @if ($quota)
                                    {{ $quota->remaining_quota }} / {{ $quota->total_quota }}
                                @else
                                    No Quota Assigned
                                @endif
                            </td>
                            <td class="px-6 py-4">{{ $proposal->proposal_title }}</td>
                            <td class="px-6 py-4 text-center">
                                <a href="/proposal/{{ $proposal->id }}"
                                    class="text-blue-500 underline hover:text-blue-700">
                                    View
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-gray-500">No proposals available</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
