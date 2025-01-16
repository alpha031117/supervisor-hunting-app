@extends('layouts.master')

@section('page', 'Lecturer Dashboard')

@section('breadcrumbs')
    <a href="{{ route('lecturer.dashboard') }}" class="hover:text-blue-600">
        <li>FYP Hunt</li>
    </a>
@endsection

@section('content')
<div class="container mx-auto px-4 py-8">
    <h3 class="text-2xl font-semibold text-gray-800 mb-6">Proposals Proposed</h3>

    @if ($lecturerProposals->isEmpty())
        <p class="text-gray-600">You haven't proposed any projects yet.</p>
    @else
        <table class="min-w-full table-auto border-collapse">
            <thead>
                <tr class="border-b">
                    <th class="py-2 px-4 text-left">Proposal Title</th>
                    <th class="py-2 px-4 text-left">Description</th>
                    <th class="py-2 px-4 text-left">Status</th>
                    <th class="py-2 px-4 text-left">Proposed Date</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($lecturerProposals as $proposal)
                    <tr class="border-b">
                        <td class="py-2 px-4">{{ $proposal->proposal_title }}</td>
                        <td class="py-2 px-4">{{ $proposal->proposal_description }}</td>
                        <td class="py-2 px-4">{{ $proposal->status }}</td>
                        <td class="py-2 px-4">{{ $proposal->created_at->format('d M, Y') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
@endsection
