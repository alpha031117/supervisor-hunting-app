@extends('layouts.master')

@section('page', 'Lecturer Quota List')

@section('breadcrumbs')
    <li>FYP Hunt</li>
    <li>/</li>
    <li>Manage Timeframe and Quota</li>
    <li>/</li>
    <li>Lecturer Quota List</li>
@endsection

@section('content')
    <div class="mt-10">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left text-gray-700 bg-transparent">
                <thead>
                    <tr>
                        <th scope="col" class="px-6 py-3 text-center">#</th>
                        <th scope="col" class="px-6 py-3">Name</th>
                        <th scope="col" class="px-6 py-3">Email</th>
                        <th scope="col" class="px-6 py-3">Program</th>
                        <th scope="col" class="px-6 py-3 text-center">Quota Left</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($lecturers as $index => $lecturer)
                        <tr class="hover:bg-gray-100 bg-transparent">
                            <td class="px-6 py-4 text-center">{{ $index + 1 }}</td>
                            <td class="px-6 py-4">{{ $lecturer->lecturer->name }}</td>
                            <td class="px-6 py-4">{{ $lecturer->lecturer->email }}</td>
                            <td class="px-6 py-4">
                                {{ $lecturer->lecturer->program->name ?? 'N/A' }}
                            </td>
                            <td class="px-6 py-4 text-center">{{ $lecturer->remaining_quota }}</td>
                        </tr>
                    @empty
                        <tr class="bg-transparent">
                            <td colspan="5" class="px-6 py-4 text-center text-gray-500">
                                No lecturer quota data available.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
