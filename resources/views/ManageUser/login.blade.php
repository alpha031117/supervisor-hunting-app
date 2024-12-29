@extends('layouts.login_master')

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
        <form method="POST" action="{{ route('login') }}" class="space-y-6">
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