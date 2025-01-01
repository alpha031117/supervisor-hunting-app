@extends('layouts.login_master')

@if ($errors->any() || session('error'))
    <div id="alert-1" class="flex items-center p-4 mb-4 text-red-500 rounded-lg bg-red-50" role="alert">
        <svg class="flex-shrink-0 w-4 h-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
            <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 1 1 1 1v4h1a1 1 0 0 1 0 2Z"/>
        </svg>
        <span class="sr-only">Error</span>
        <div class="ms-3 text-sm font-medium">
            @if ($errors->any())
                @foreach ($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            @elseif (session('error'))
                <p>{{ session('error') }}</p>
            @endif
        </div>
        <button type="button" class="ms-auto -mx-1.5 -my-1.5 bg-red-50 text-red-500 rounded-lg focus:ring-2 focus:ring-red-400 p-1.5 hover:bg-red-200 inline-flex items-center justify-center h-8 w-8" data-dismiss-target="#alert-1" aria-label="Close">
            <span class="sr-only">Close</span>
            <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
            </svg>
        </button>
    </div>
@endif


@section('content')
<div class="flex flex-col md:flex-row bg-white rounded-md overflow-hidden w-full max-w-5xl md:gap-12">
    <!-- Left Illustration Section -->
    <div class="w-full md:w-1/2 flex items-center justify-center">
        <img src="{{ asset('images/login_illustration.gif') }}" alt="Illustration" class="w-full h-auto">
    </div>

    <!-- Login Form Section -->
    <div class="w-full md:w-1/2 p-8 flex flex-col justify-center border border-color rounded-lg">
        <div class="text-center mb-6">
            <div class="flex items-center justify-center space-x-4">
                <!-- Icon -->
                <img src="{{ asset('images/icon.png') }}" alt="Logo" class="w-16 h-auto">
                <h1 class="text-2xl font-bold">FYP Hunt</h1>
            </div>
        </div>        
        <form method="POST" action="{{ route('auth.login') }}" class="space-y-6">
            @csrf
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                <input type="email" id="email" name="email" value="charlenereed@umpsa.edu.com" 
                       class="w-full mt-1 px-2 py-2 border-b border-gray-300 focus:outline-none focus:ring-0 focus:border-blue-500" required>
            </div>
            <div>
                <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                <input type="password" id="password" name="password" value="********" 
                       class="w-full mt-1 px-2 py-2 border-b border-gray-300 focus:outline-none focus:ring-0 focus:border-blue-500" required>
            </div>
            <div class="text-sm text-right text-gray-600">
                <p>Don’t Have Account?</p>
                <span>
                    <a href="{{ route('reset-password')}}" class="text-blue-600 hover:underline">Register Here</a>
                </span>
            </div>
            <button type="submit" class="w-full bg-blue-600 text-white py-2 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                Login Now
            </button>
        </form>
    </div>
</div>
@endsection