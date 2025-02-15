@extends('layouts.master')

@section('page', 'Notification')

@section('breadcrumbs')
    <a href="{{ route('student.dashboard') }}" class="hover:text-blue-600">
        <li>FYP Hunt</li>
    </a>
    <li>/</li>
    <a href="{{ route('student.dashboard') }}" class="hover:text-blue-600">
        <li>Notification</li>
    </a>
@endsection

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Tabs for navigation -->
    <div class="mb-6">
        <ul class="flex border-b border-gray-300">
            <li class="mr-6">
                <a href="#all" class="text-lg font-medium text-gray-800 hover:text-blue-600 py-2 px-4 inline-block cursor-pointer" onclick="filterNotifications('all')">All</a>
            </li>
            <li class="mr-6">
                <a href="#read" class="text-lg font-medium text-gray-800 hover:text-blue-600 py-2 px-4 inline-block cursor-pointer" onclick="filterNotifications('read')">Read</a>
            </li>
            <li>
                <a href="#unread" class="text-lg font-medium text-gray-800 hover:text-blue-600 py-2 px-4 inline-block cursor-pointer" onclick="filterNotifications('unread')">Unread</a>
            </li>
        </ul>
    </div>

    <!-- Notifications Table Section -->
    <div id="notificationTab" class="overflow-x-auto">
        <!-- All Notifications Section -->
        <div id="all" class="tab-content hidden">
            <h3 class="text-2xl font-semibold text-gray-800 mb-4">All Notifications</h3>
            @if ($notifications->isEmpty())
                <p class="text-gray-600">No notifications available.</p>
            @else
                <table class="min-w-full table-auto border-collapse">
                    <thead>
                        <tr class="border-b">
                            <th class="py-2 px-4 text-left">Title</th>
                            <th class="py-2 px-4 text-left">Content</th>
                            <th class="py-2 px-4 text-left">Date</th>
                            <th class="py-2 px-4 text-left">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($notifications as $notification)
                            <tr class="border-b">
                                <td class="py-2 px-4">{{ $notification->title }}</td>
                                <td class="py-2 px-4">{{ $notification->content }}</td>
                                <td class="py-2 px-4">{{ $notification->created_at->format('d M, Y') }}</td>
                                <td class="py-2 px-4">{{ $notification->status }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>

        <!-- Read Notifications Section -->
        <div id="read" class="tab-content hidden">
            <h3 class="text-2xl font-semibold text-gray-800 mb-4">Read Notifications</h3>
            @if ($readNotifications->isEmpty())
                <p class="text-gray-600">No read notifications available.</p>
            @else
                <table class="min-w-full table-auto border-collapse">
                    <thead>
                        <tr class="border-b">
                            <th class="py-2 px-4 text-left">Title</th>
                            <th class="py-2 px-4 text-left">Content</th>
                            <th class="py-2 px-4 text-left">Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($readNotifications as $notification)
                            <tr class="border-b">
                                <td class="py-2 px-4">{{ $notification->title }}</td>
                                <td class="py-2 px-4">{{ $notification->content }}</td>
                                <td class="py-2 px-4">{{ $notification->created_at->format('d M, Y') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>

        <!-- Unread Notifications Section -->
        <div id="unread" class="tab-content hidden">
            <h3 class="text-2xl font-semibold text-gray-800 mb-4">Unread Notifications</h3>
            @if ($unreadNotifications->isEmpty())
                <p class="text-gray-600">No unread notifications available.</p>
            @else
                <table class="min-w-full table-auto border-collapse">
                    <thead>
                        <tr class="border-b">
                            <th class="py-2 px-4 text-left">Title</th>
                            <th class="py-2 px-4 text-left">Content</th>
                            <th class="py-2 px-4 text-left">Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($unreadNotifications as $notification)
                            <tr class="border-b">
                                <td class="py-2 px-4">{{ $notification->title }}</td>
                                <td class="py-2 px-4">{{ $notification->content }}</td>
                                <td class="py-2 px-4">{{ $notification->created_at->format('d M, Y') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    </div>
</div>

<script>
    function filterNotifications(tab) {
        // Hide all tabs
        document.querySelectorAll('.tab-content').forEach(function(tabContent) {
            tabContent.classList.add('hidden');
        });

        // Show the selected tab
        document.getElementById(tab).classList.remove('hidden');
    }

    // Default: Show all notifications
    filterNotifications('all');
</script>

@endsection
