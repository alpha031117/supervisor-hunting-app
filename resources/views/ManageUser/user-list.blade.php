@extends('layouts.master')

@section('page', 'Manage User')

@section('breadcrumbs')
    <li>FYP Hunt</li>
    <li>/</li>
    <li>Manage User</li>
@endsection

@section('content')
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-semibold">List of Registered Users</h1>
        <div class="space-x-4">
            <button class="border border-blue-500 text-blue-500 px-4 py-2 rounded-lg hover:bg-blue-100">
                Generate Report
            </button>
            <button class="border border-blue-500 text-blue-500 px-4 py-2 rounded-lg hover:bg-blue-100">
                Register New User
            </button>
        </div>
    </div>

    <!-- Tabs -->
    <div class="flex space-x-6 mb-4 border-b">
        <button id="all-tab" onclick="filterTable('all')" class="tab-btn text-blue-500 font-medium border-b-2 border-blue-500 px-4 py-2">
            All
        </button>
        <button id="students-tab" onclick="filterTable('Student')" class="tab-btn text-gray-500 hover:text-blue-500 px-4 py-2">
            Students
        </button>
        <button id="lecturers-tab" onclick="filterTable('Lecturer')" class="tab-btn text-gray-500 hover:text-blue-500 px-4 py-2">
            Lecturers
        </button>
    </div>

    <!-- Table -->
    <div class="overflow-x-auto">
        <table id="user-table" class="table-auto w-full text-left">
            <thead class="bg-gray-100">
                <tr>
                    <th class="px-4 py-2 text-sm font-medium text-gray-700">No</th>
                    <th class="px-4 py-2 text-sm font-medium text-gray-700">Name</th>
                    <th class="px-4 py-2 text-sm font-medium text-gray-700">Email</th>
                    <th class="px-4 py-2 text-sm font-medium text-gray-700">Program</th>
                    <th class="px-4 py-2 text-sm font-medium text-gray-700">Role</th>
                    <th class="px-4 py-2 text-sm font-medium text-gray-700 text-center justify-center">Action</th>
                </tr>
            </thead>
            <tbody class="space-y-4">
                @foreach ($users as $user)
                <tr class="border-b h-16" data-role="{{ $user['role'] }}">
                    <td class="px-4 py-2 text-sm text-gray-700">{{ $loop->iteration }}</td>
                    <td class="px-4 py-2 text-sm text-gray-700">{{ $user['name'] }}</td>
                    <td class="px-4 py-2 text-sm text-gray-700">{{ $user['email'] }}</td>
                    <td class="px-4 py-2 text-sm text-gray-700">{{ $user['program'] }}</td>
                    <td class="px-4 py-2 text-sm text-gray-700">{{ $user['role'] }}</td>
                    <td class="px-4 py-4 flex items-center justify-center">
                        <button class="{{ $user['action'] === 'Assigned' ? 'text-green-500 border border-green-500 px-4 py-1 rounded-lg bg-green-100' : 'text-blue-500 border border-blue-500 px-4 py-1 rounded-lg hover:bg-blue-100' }}">
                            {{ $user['action'] }}
                        </button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Pagination Links -->
    <div class="mt-6">
        {{ $users->links('vendor.pagination.tailwind') }}
    </div>

    <!-- JavaScript for Filtering -->
    <script>
        function filterTable(role) {
            const rows = document.querySelectorAll('#user-table tbody tr');
            const tabs = document.querySelectorAll('.tab-btn');

            // Filter rows
            rows.forEach(row => {
                if (role === 'all' || row.getAttribute('data-role') === role) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });

            // Update active tab styles
            tabs.forEach(tab => {
                tab.classList.remove('text-blue-500', 'font-medium', 'border-b-2', 'border-blue-500');
                tab.classList.add('text-gray-500');
            });

            // Highlight active tab
            document.getElementById(`${role.toLowerCase()}-tab`).classList.add('text-blue-500', 'font-medium', 'border-b-2', 'border-blue-500');
        }

        // Initialize "All" tab as active on page load
        document.addEventListener('DOMContentLoaded', () => {
            filterTable('all');
        });
    </script>
@endsection
