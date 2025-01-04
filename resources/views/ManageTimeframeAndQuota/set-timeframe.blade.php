@extends('layouts.master')

@section('page', 'Set Timeframe')

@section('breadcrumbs')
    <li>FYP Hunt</li>
    <li>/</li>
    <li>Set Timeframe</li>
@endsection

@section('content')
    <div class="mt-10">
        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4" role="alert">
                {{ session('success') }}
            </div>
        @endif

        <form action="{{ route('store-timeframe') }}" method="POST">
            @csrf
            <div class="grid grid-cols-3 gap-4 items-center">
                <!-- Start Date -->
                <div>
                    <label for="start_date" class="block text-gray-700 font-medium mb-2">Start Date</label>
                    <input type="date" id="start_date" name="start_date" value="{{ $currentPeriod->start_date ?? '' }}"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2" required>
                </div>

                <!-- Arrow -->
                <div class="flex justify-center">
                    <x-heroicon-o-arrow-right class="w-8 h-8 text-gray-600" />
                </div>

                <!-- End Date -->
                <div>
                    <label for="end_date" class="block text-gray-700 font-medium mb-2">End Date</label>
                    <input type="date" id="end_date" name="end_date" value="{{ $currentPeriod->end_date ?? '' }}"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2" required>
                </div>
            </div>

            <div class="mt-6 flex justify-end">
                <button type="submit"
                    class="px-4 py-2 bg-blue-600 text-white rounded-lg shadow hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    Save Timeframe
                </button>
            </div>
        </form>
    </div>
@endsection
