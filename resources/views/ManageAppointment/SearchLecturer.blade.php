@extends('layouts.master')

@section('content')

<div class="container mx-auto my-10">
    <!-- Header Section -->
    <div class="text-center mb-10">
        <h1 class="text-4xl font-extrabold" style="color: #1814F3;">Search Your Lecturer</h1>
        <p class="text-lg text-gray-500 mt-2">Find and book appointments with your lecturers quickly.</p>
    </div>

    <!-- Search Section -->
    <div class="flex justify-center mb-8">
        <div class="w-full max-w-2xl">
            <div class="bg-white shadow-md rounded-full px-6 py-4">
                <form id="searchForm" action="{{ route('search') }}" method="GET" class="flex space-x-4">
                    <input 
                        type="text" 
                        name="search" 
                        class="flex-1 rounded-full border-gray-300 shadow-sm focus:ring focus:ring-blue-200 focus:outline-none px-5 py-2 text-sm"
                        placeholder="Search by Lecturer Name" 
                        value="{{ $search }}">
                    <button 
                        type="submit" 
                        class="text-white font-semibold rounded-full px-6 py-2 text-sm hover:shadow-lg transition"
                        style="background-color: #1814F3;">
                        Search
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Table Section -->
    <div class="bg-white shadow-lg rounded-lg">
        <div class="text-white font-bold rounded-t-lg px-6 py-4"
             style="background-color: #1814F3;">
            Lecturer Directory
        </div>
        <div class="p-6">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead class="bg-gray-100 text-gray-700 text-sm uppercase font-semibold">
                        <tr>
                            <th class="px-6 py-3">Lecturer Name</th>
                            <th class="px-6 py-3">Email</th>
                            <th class="px-6 py-3 text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 text-gray-600 text-sm">
                        @if ($lecturers->isNotEmpty())
                            @foreach ($lecturers as $lecturer)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-3 font-medium">{{ $lecturer->name }}</td>
                                    <td class="px-6 py-3">{{ $lecturer->email }}</td>
                                    <td class="px-6 py-3 text-center">
                                        <a 
                                            href="{{ route('applyappointment.create', ['lecturer' => $lecturer->id]) }}" 
                                            class="inline-block text-white text-sm font-semibold rounded-full px-4 py-2 hover:shadow-md transition"
                                            style="background-color: #1814F3;">
                                            Book Appointment
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="3" class="px-6 py-3 text-center text-gray-500">No results found.</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Pagination Links -->
    <div class="flex justify-center mt-6">
        {{ $lecturers->appends(['search' => request('search')])->links('pagination::tailwind') }}
    </div>
</div>

<script>
    document.getElementById('searchForm').addEventListener('submit', function (e) {
        const searchInput = document.querySelector('input[name="search"]').value.trim();

        // Regular expression to detect numbers
        const containsNumbers = /\d/;

        if (containsNumbers.test(searchInput)) {
            // Prevent form submission if the input contains numbers
            e.preventDefault();
            alert("Please enter appropriate information (letters only).");
            return false;
        }

        if (searchInput === "") {
            // Prevent form submission if input is empty
            e.preventDefault();
            alert("Please enter appropriate information.");
            return false;
        }

        // Submit the form, but check if no lecturers are returned (handled on server-side)
        // If no lecturers are found, you can display 'Lecturer not found' message in the table (already in the Blade template)
    });
</script>
@endsection
