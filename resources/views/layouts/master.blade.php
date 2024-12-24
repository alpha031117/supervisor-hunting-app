<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'FYP Hunt')</title>
    @vite('resources/css/app.css')
</head>

<body class="bg-gray-50">
    <div class="flex h-screen pt-5 px-5">
        <!-- Sidebar -->
        @include('partials.sidebar')

        <!-- Main Content -->
        <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-50">
            <!-- Header -->
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

    </div>

    {{-- Footer --}}
    @include('partials.footer')
</body>

</html>
