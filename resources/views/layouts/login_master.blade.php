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
    @include('partials.footer')
</body>

@yield('scripts')
</html>
