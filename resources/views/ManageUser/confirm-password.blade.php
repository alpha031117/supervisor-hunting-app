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
                <div class="border-2 border-blue-600 text-blue-600 w-8 h-8 rounded-full flex items-center justify-center font-bold">2</div>
                <div class="w-16 border-t-2 border-gray-300"></div>
            </div>
            <!-- Step 3 -->
            <div>
                <div class="border-2 border-gray-300 text-gray-400 w-8 h-8 rounded-full flex items-center justify-center font-bold">3</div>
            </div>
        </div>

        <!-- Reset Password Form -->
        <form method="POST" action="{{ route('confirm-password') }}" class="space-y-6">
            <div>
                <label for="new_password" class="block text-sm font-medium text-gray-700">Re-enter Password</label>
                <input type="password" id="new_password" name="new_password" placeholder="********" 
                       class="w-full mt-1 px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-blue-500 focus:border-blue-500" required>
            </div>
            <button type="submit" 
                    class="w-full bg-blue-600 text-white py-2 rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                Next
            </button>
        </form>
    </div>
</div>
@endsection