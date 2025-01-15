<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Preline and Flowbite -->
    <script src="https://cdn.jsdelivr.net/npm/preline"></script>
    <link href="https://cdn.jsdelivr.net/npm/flowbite@2.5.2/dist/flowbite.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/flowbite@2.5.2/dist/flowbite.min.js"></script>

    <title>@yield('title', 'FYP Hunt')</title>
    @vite('resources/css/app.css')

    <style>
        footer {
            position: fixed;
            bottom: 0;
            left: 0;
            width: calc(100% - 16rem);
            /* Exclude sidebar width */
            margin-left: 16rem;
            /* Adjust to align with main content */
            z-index: -1;
            /* Send footer behind sidebar */
            background-color: white;
            /* Ensure visibility */
        }
    </style>
</head>

<body class="min-h-screen flex flex-col">
    <!-- Sidebar -->
    <div class="w-64 shadow-lg fixed top-0 left-0 h-screen z-10">
        @include('partials.sidebar')
    </div>

    <!-- Main Content -->
    <main class="flex-1 ml-64">
        @include('partials.header')

        <!-- Page Content -->
        <div class="p-6 flex-grow">
            {{-- Breadcrumb --}}
            <nav class="text-gray-500" aria-label="Breadcrumb">
                <ol class="flex items-center space-x-2">
                    @yield('breadcrumbs')
                </ol>
            </nav>
            <div class="mt-5">
                @yield('content')
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="text-black text-center py-4">
        <p>&copy; {{ date('Y') }} FYP Hunt. All rights reserved.</p>
    </footer>
</body>

</html>
