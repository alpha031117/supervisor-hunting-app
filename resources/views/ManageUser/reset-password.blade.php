@extends('layouts.login_master')
@section('title', 'Reset Password')
<div id="alert-1" class="flex items-center p-4 mb-4 text-blue-500 rounded-lg bg-blue-50" role="alert">
    <svg class="flex-shrink-0 w-4 h-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
        <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z"/>
    </svg>
    <span class="sr-only">Info</span>
    <div class="ms-3 text-sm font-medium">
       Since you are first time login, please reset your password.
    </div>
    <button type="button" class="ms-auto -mx-1.5 -my-1.5 bg-blue-50 text-blue-500 rounded-lg focus:ring-2 focus:ring-blue-400 p-1.5 hover:bg-blue-200 inline-flex items-center justify-center h-8 w-8" data-dismiss-target="#alert-1" aria-label="Close">
        <span class="sr-only">Close</span>
        <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
        </svg>
    </button>
</div>
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
                {{-- <div class="bg-blue-600 text-white w-8 h-8 rounded-full flex items-center justify-center font-bold">1</div> --}}
                {{-- <div class="w-16 border-t-2 border-blue-600"></div> --}}
                <div class="border-2 border-blue-600 text-blue-600 w-8 h-8 rounded-full flex items-center justify-center font-bold">1</div>
                <div class="w-16 border-t-2 border-gray-300"></div>
            </div>
            <!-- Step 2 -->
            <div class="flex items-center">
                <div class="border-2 border-gray-300 text-gray-400 w-8 h-8 rounded-full flex items-center justify-center font-bold">2</div>
                <div class="w-16 border-t-2 border-gray-300"></div>
            </div>
            <!-- Step 3 -->
            <div>
                <div class="border-2 border-gray-300 text-gray-400 w-8 h-8 rounded-full flex items-center justify-center font-bold">3</div>
            </div>
        </div>

        <!-- Reset Password Form -->
        <form method="POST" action="{{ route('store-session') }}" class="space-y-6">
            @csrf
            <div>
                <label for="new_password" class="block text-sm font-medium text-gray-700">New Password</label>
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
@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            document.querySelectorAll('[data-dismiss-target]').forEach(button => {
                button.addEventListener('click', function () {
                    const target = document.querySelector(this.getAttribute('data-dismiss-target'));
                    if (target) {
                        target.remove(); // Removes the alert element from the DOM
                    }
                });
            });
        });
    </script>
@endsection