<!-- resources/views/ManageTimeframeAndQuota/lecturer-quota-list.blade.php -->

@extends('layouts.master')

@section('page', 'Lecturer Quota List')

@section('breadcrumbs')
    <a href="{{ route('coordinator.dashboard') }}" class="hover:text-blue-600">
        <li>FYP Hunt</li>
    </a>
    <li>/</li>
    <a href="{{ route('lecturer-quota-list') }}" class="text-blue-600">
        <li>Lecturer Quota List</li>
    </a>
@endsection

@section('content')
    <div class="mt-10">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left text-gray-700 bg-transparent">
                <thead class="bg-gray-100">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-center">No</th>
                        <th scope="col" class="px-6 py-3">Name</th>
                        <th scope="col" class="px-6 py-3">Email</th>
                        <th scope="col" class="px-6 py-3">Program</th>
                        <th scope="col" class="px-6 py-3 text-center">Total Quota</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($lecturers as $index => $lecturer)
                        <tr class="border-b h-16">
                            <td class="px-6 py-4 text-center">{{ $index + 1 }}</td>
                            <td class="px-6 py-4">{{ $lecturer->lecturer->name }}</td>
                            <td class="px-6 py-4">{{ $lecturer->lecturer->email }}</td>
                            <td class="px-6 py-4">
                                {{ $lecturer->lecturer->program->name ?? 'N/A' }}
                            </td>
                            <td class="px-6 py-4 text-center">{{ $lecturer->total_quota }}</td>
                        </tr>
                    @empty
                        <tr class="border-b h-16">
                            <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                                No lecturer quota data available.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
