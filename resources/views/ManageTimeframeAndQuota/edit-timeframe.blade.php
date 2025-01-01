@extends('layouts.master')

@section('page', 'Edit Timeframe')

@section('breadcrumbs')
    <li>FYP Hunt</li>
    <li>/</li>
    <li>Edit Timeframe</li>
@endsection

@section('content')
    <div class="flex justify-start mb-6">
        <!-- Dropdown Button -->
        <button id="dropdownDefaultButton" data-dropdown-toggle="dropdown"
            class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 w-52 text-center flex items-center justify-between dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800"
            type="button">
            Semester
            <svg class="w-4 h-4 ml-2" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 6">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="m1 1 4 4 4-4" />
            </svg>
        </button>

        <!-- Dropdown Menu -->
        <div id="dropdown" class="z-10 hidden bg-white divide-y divide-gray-100 rounded-lg shadow w-52 dark:bg-gray-700">
            <ul class="py-2 text-sm text-gray-700 dark:text-gray-200" aria-labelledby="dropdownDefaultButton">
                <li>
                    <a href="#"
                        class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Semester I
                        2024/25</a>
                </li>
                <li>
                    <a href="#"
                        class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Semester II
                        2024/25</a>
                </li>
                <li>
                    <a href="#"
                        class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Semester III
                        2024/25</a>
                </li>
            </ul>
        </div>
    </div>

    <!-- Main Section -->
    <div class="mt-10 flex justify-center items-center">
        <div class="grid grid-cols-3 gap-4 items-center">
            <!-- Start Date -->
            <div>
                <label for="start-date" class="block text-gray-700 font-medium mb-2 text-left">Start Date</label>
                <div id="datepicker-start-date" inline-datepicker data-date="01/1/2024" class=""></div>
            </div>

            <!-- Arrow -->
            <div class="flex justify-center">
                <x-heroicon-o-arrow-right class="w-8 h-8 text-gray-600" />
            </div>

            <!-- End Date -->
            <div class="flex flex-col">
                <label for="end-date" class="block text-gray-700 font-medium mb-2 text-left">End Date</label>
                <div id="datepicker-end-date" inline-datepicker data-date="01/25/2024" class="mb-4"></div>

                <!-- Save Button -->
                <div class="flex justify-end">
                    <button
                        class="px-4 py-2 bg-blue-600 text-white rounded-lg shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        Update Timeframe
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection
