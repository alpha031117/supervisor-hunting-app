<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://cdn.jsdelivr.net/npm/preline"></script>
    <link href="https://cdn.jsdelivr.net/npm/flowbite@2.5.2/dist/flowbite.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/flowbite@2.5.2/dist/flowbite.min.js"></script>
    <title>@yield('title', 'FYP Hunt')</title>
    @vite('resources/css/app.css')
</head>

<body class="bg-gray-50 h-screen flex">
    <!-- Sidebar -->
    @include('partials.sidebar')

    <!-- Main Content -->
    <main class="flex-1 bg-gray-50 mb-5">
        @include('partials.header')

        <!-- Page Content -->
        <div class="p-6">
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
    @include('partials.footer')
</body>

</html>
