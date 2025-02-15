@extends('layouts.master')

@section('page', 'Student Applications')

@section('breadcrumbs')
    <a href="{{ route('student.dashboard') }}" class="hover:text-blue-600">
        <li>FYP Hunt</li>
    </a>
@endsection

@section('content')
<div class="container mx-auto px-4 py-8">
    <h3 class="text-2xl font-semibold text-gray-800 mb-6">Your Applications</h3>

    @if ($studentApplications->isEmpty())
        <p class="text-gray-600">You have not applied for any proposal yet.</p>
    @else
        <table class="min-w-full table-auto border-collapse">
            <thead>
                <tr class="border-b">
                    <th class="py-2 px-4 text-left">Lecturer Name</th>
                    <th class="py-2 px-4 text-left">Application Title</th>
                    <th class="py-2 px-4 text-left">Description</th>
                    <th class="py-2 px-4 text-left">Status</th>
                    <th class="py-2 px-4 text-left">Decision Date</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($studentApplications as $application)
                    <tr class="border-b">
                        <td class="py-2 px-4">{{ $application->lecturer->name ?? 'N/A' }}</td>
                        <td class="py-2 px-4">{{ $application->student_title }}</td>
                        <td class="py-2 px-4">{{ $application->student_description }}</td>
                        <td class="py-2 px-4">{{ $application->status }}</td>
                        <td class="py-2 px-4">
                            {{ $application->decision_date ? \Carbon\Carbon::parse($application->decision_date)->format('d M, Y') : 'Pending' }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
@endsection
