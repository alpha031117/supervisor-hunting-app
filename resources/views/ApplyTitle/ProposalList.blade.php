@extends('layouts.master')

@section('content')

<h1>List of Proposal Title</h1>
<table class="min-w-full bg-white border border-gray-200">
    <thead>
        <tr class="bg-gray-100">
            <th class="text-left px-6 py-3 border-b">No</th>
            <th class="text-left px-6 py-3 border-b">Lecturer</th>
            <th class="text-left px-6 py-3 border-b">Proposed Title</th>
            <th class="text-left px-6 py-3 border-b">Lecturer Quota</th>
            <th class="text-center px-6 py-3 border-b">Action</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($proposals as $index => $proposal)
            <tr class="{{ $loop->even ? 'bg-gray-50' : '' }}">
                <td class="px-6 py-4 border-b">{{ $index + 1 }}</td>
                <td class="px-6 py-4 border-b">{{ $proposal->lecturer->name }}</td>
                <td class="px-6 py-4 border-b">{{ $proposal->proposal_title }}</td>
                <td class="px-6 py-4 border-b text-center">
                    {{ $proposal->lecturer->lecturerQuotas->first()->remaining_quota ?? 'N/A' }}
                </td>
                <td class="px-6 py-4 border-b text-center">
                    <a href="{{ route('proposals.view', $proposal->id) }}"
                       class="text-blue-500 underline hover:text-blue-700">
                        View
                    </a>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>

@endsection
