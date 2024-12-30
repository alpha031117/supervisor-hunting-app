@extends('layouts.master')

@section('page', 'Set Timeframe')

@section('breadcrumbs')
    <li>FYP Hunt</li>
    <li>/</li>
    <li>Set Timeframe</li>
@endsection

@section('content')
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
                        Save Timeframe
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection
