<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'FYP Hunt')</title>
    <script src="https://cdn.jsdelivr.net/npm/preline"></script>
    @vite('resources/css/app.css')
</head>

<body class="bg-white flex flex-col min-h-screen">

    <!-- Main Content -->
    <main class="flex-grow flex items-center justify-center">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="text-black text-center py-4 fixed bottom-0 left-0 w-full">
        <p>&copy; {{ date('Y') }} FYP Hunt. All rights reserved.</p>
    </footer>
</body>

@yield('scripts')
</html>
