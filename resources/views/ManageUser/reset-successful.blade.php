@extends('layouts.login_master')
@section('title', 'Reset Password')

@section('content')
<div class="flex flex-col md:flex-row bg-white rounded-md overflow-hidden w-full max-w-5xl md:gap-12">
    <!-- Reset Password Card -->
    <div class="bg-white border border-gray-200 rounded-lg shadow-md w-full p-8">
    <!-- Logo and Title -->
    <div class="text-center mb-8">
        <div class="flex items-center justify-center space-x-4">
            <!-- Icon -->
            <img src="{{ asset('images/icon.png') }}" alt="Logo" class="w-16 h-auto">
            <h1 class="text-2xl font-bold">FYP Hunt</h1>
        </div>
    </div>

    <!-- Progress Bar -->
    <div class="flex items-center justify-center mb-8 space-x-4">
        <!-- Step 1 -->
        <div class="flex items-center">
            <div class="bg-blue-600 text-white w-8 h-8 rounded-full flex items-center justify-center font-bold">
                {{-- Tick SVG --}}
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="4" d="M5 13l4 4L19 7"/>
                </svg>
            </div>
            <div class="w-16 border-t-2 border-blue-600"></div>
        </div>
        <!-- Step 2 -->
        <div class="flex items-center">
            <div class="bg-blue-600 text-white w-8 h-8 rounded-full flex items-center justify-center font-bold">
                {{-- Tick SVG --}}
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="4" d="M5 13l4 4L19 7"/>
                </svg>
            </div>
            <div class="w-16 border-t-2 border-blue-600"></div>
        </div>
        <!-- Step 3 -->
        <div>
            <div class="bg-blue-600 text-white w-8 h-8 rounded-full flex items-center justify-center font-bold">
                {{-- Tick SVG --}}
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="4" d="M5 13l4 4L19 7"/>
                </svg>
            </div>
        </div>
    </div>

        {{-- Success Message --}}
        <p class="text-center text-green-500 font-semibold mb-5">Congratulations, you had activated your account!</p>
        <p class="text-center text-gray-600">You may proceed to login now.</p>

    {{-- Login Button --}}
    <div class="flex justify-center mt-8">
        <a href="{{ route('auth.login') }}" class="bg-blue-600 text-white py-2 px-4 rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">Login Now</a>
    </div>
</div>
@endsection