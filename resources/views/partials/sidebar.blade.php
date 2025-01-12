<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

<button data-drawer-target="logo-sidebar" data-drawer-toggle="logo-sidebar" aria-controls="logo-sidebar" type="button"
    class="inline-flex items-center p-2 mt-2 ms-3 text-sm text-gray-500 rounded-lg sm:hidden hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200 dark:text-gray-400 dark:hover:bg-gray-700 dark:focus:ring-gray-600">
    <span class="sr-only">Open sidebar</span>
    <svg class="w-6 h-6" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
        <path clip-rule="evenodd" fill-rule="evenodd"
            d="M2 4.75A.75.75 0 012.75 4h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 4.75zm0 10.5a.75.75 0 01.75-.75h7.5a.75.75 0 010 1.5h-7.5a.75.75 0 01-.75-.75zM2 10a.75.75 0 01.75-.75h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 10z">
        </path>
    </svg>
</button>

<aside id="logo-sidebar"
    class="fixed top-0 left-0 z-40 w-64 h-screen shadow-xl transition-transform -translate-x-full sm:translate-x-0"
    aria-label="Sidebar">
    <div class="h-full px-3 py-4 overflow-y-auto dark:bg-gray-800">
        <a href="" class="flex items-center ps-2.5 mb-5">
            <img src="{{ asset('images/icon.png') }}" class="h-10 w-10 me-3 sm:h-12 sm:w-12" alt="FYP Hunt Logo" />
            <span class="self-center text-xl font-semibold whitespace-nowrap dark:text-white">FYP Hunt</span>
        </a>
        <ul class="space-y-2 font-medium mt-10">
            <li>
                <a href="#"
                    class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group">
                    <x-heroicon-s-home
                        class="w-7 h-7 text-gray-500 transition duration-75 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white" />
                    <span class="ms-3 text-gray-500 dark:text-gray-400 group-hover:text-gray-900">Dashboard</span>
                </a>
            </li>
            <li>
                <a href="{{ route('admin.user-list') }}"
                    class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group  {{ Request::routeIs('admin.user-list') ? 'bg-blue-100' : '' }}">
                    <x-heroicon-s-users
                        class="w-7 h-7 text-gray-500 transition duration-75 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white" />
                    <span class="ms-3 text-gray-500 dark:text-gray-400 group-hover:text-gray-900">User</span>
                </a>
            </li>
            <li>
                <div 
                    x-data="{ 
                        open: {{ Request::routeIs('search', 'appointments.myAppointments', 'schedule.upload.form', 'lecturer.responseAppointment') ? 'true' : 'false' }} 
                    }" 
                    class="relative"
                >
                    <button 
                        @click="open = !open" 
                        class="flex items-center p-2 text-gray-500 rounded-lg dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 group"
                    >
                        <svg class="w-6 h-6 mr-3 text-gray-500 transition duration-75 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white"
                            xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M3 3h18v2H3V3zm3 8h12V8H6v3zm0 6h8v-2H6v2zm14-4H4v8h16v-8zm0-2H4a1 1 0 0 1-1-1v-4a1 1 0 0 1 1-1h16a1 1 0 1 1-1-1v-4a1 1 0 1 1-1-1z" />
                        </svg>
                        <span class="text-gray-500 transition duration-75 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white">
                            Manage Appointment
                        </span>
                    </button>
            
                    <!-- Dropdown menu -->
                    <div x-show="open" x-cloak x-transition @click.away="open = false" class="mt-2 space-y-1 pl-8">
                        @if (Auth::check())
                            @php
                                $role = Auth::user()->role;
                            @endphp
            
                            @if ($role === 'student')
                                <a href="{{ url('/search') }}"
                                    class="flex items-center px-4 py-2 rounded-lg transition hover:bg-gray-100 dark:hover:bg-gray-700 group
                                    {{ Request::is('search') ? 'bg-gray-200 dark:bg-gray-800 text-blue-600 dark:text-blue-400' : 'text-gray-500 dark:text-gray-400' }}">
                                    <svg class="w-5 h-5 mr-3 {{ Request::is('search') ? 'text-blue-600 dark:text-blue-400' : 'text-gray-500 dark:text-gray-400' }}"
                                        xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M10 2a8 8 0 105.293 14.707l5.025 5.025 1.415-1.415-5.025-5.025A8 8 0 0010 2zm0 2a6 6 0 110 12 6 6 0 010-12z" />
                                    </svg>
                                    <span class="transition duration-75">
                                        Search Lecturer
                                    </span>
                                </a>
                                <a href="{{ route('appointments.myAppointments') }}"
                                    class="flex items-center px-4 py-2 rounded-lg transition hover:bg-gray-100 dark:hover:bg-gray-700 group
                                    {{ Request::routeIs('appointments.myAppointments') ? 'bg-gray-200 dark:bg-gray-800 text-blue-600 dark:text-blue-400' : 'text-gray-500 dark:text-gray-400' }}">
                                    <svg class="w-5 h-5 mr-3 {{ Request::routeIs('appointments.myAppointments') ? 'text-blue-600 dark:text-blue-400' : 'text-gray-500 dark:text-gray-400' }}"
                                        xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M7 12a5 5 0 1110 0v6a3 3 0 006 0v-6a5 5 0 10-10 0v6a3 3 0 00-6 0v-6zm5-9a3 3 0 00-3 3h2a1 1 0 012 0h2a3 3 0 00-3-3zm0 18a5 5 0 00-5-5v2a3 3 0 013 3h2a3 3 0 013-3v-2a5 5 0 00-5 5z" />
                                    </svg>
                                    <span class="transition duration-75">
                                        My Appointments
                                    </span>
                                </a>
                            @elseif ($role === 'lecturer')
                                <a href="{{ route('schedule.upload.form') }}"
                                    class="flex items-center px-4 py-2 rounded-lg transition hover:bg-gray-100 dark:hover:bg-gray-700 group
                                    {{ Request::routeIs('schedule.upload.form') ? 'bg-gray-200 dark:bg-gray-800 text-blue-600 dark:text-blue-400' : 'text-gray-500 dark:text-gray-400' }}">
                                    <svg class="w-5 h-5 mr-3 {{ Request::routeIs('schedule.upload.form') ? 'text-blue-600 dark:text-blue-400' : 'text-gray-500 dark:text-gray-400' }}"
                                        xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M3 3h18v2H3V3zm2 4v4h4V7H5zm10 0v4h4V7h-4zM7 13v4h4v-4H7zm6 0v4h4v-4h-4zM3 19h18v2H3v-2z" />
                                    </svg>
                                    <span class="transition duration-75">
                                        Timetable
                                    </span>
                                </a>
                                <a href="{{ route('lecturer.responseAppointment') }}"
                                    class="flex items-center px-4 py-2 rounded-lg transition hover:bg-gray-100 dark:hover:bg-gray-700 group
                                    {{ Request::routeIs('lecturer.responseAppointment') ? 'bg-gray-200 dark:bg-gray-800 text-blue-600 dark:text-blue-400' : 'text-gray-500 dark:text-gray-400' }}">
                                    <svg class="w-5 h-5 mr-3 {{ Request::routeIs('lecturer.responseAppointment') ? 'text-blue-600 dark:text-blue-400' : 'text-gray-500 dark:text-gray-400' }}"
                                        xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M3 4h18v2H3V4zm0 4h18v2H3V8zm0 4h12v2H3v-2zm0 4h18v2H3v-2zm0 4h12v2H3v-2z" />
                                    </svg>
                                    <span class="transition duration-75">
                                        Appointment Request
                                    </span>
                                </a>
                            @endif
                        @endif
                    </div>
                </div>
            </li>
            
            
                <a href="#"
                    class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group">
                    <x-iconoir-graduation-cap-solid
                        class="w-7 h-7 text-gray-500 transition duration-75 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white" />
                    <span class="ms-3 text-gray-500 dark:text-gray-400 group-hover:text-gray-900">Topic Approval</span>
                </a>
            </li>
            <li>
                <button type="button"
                    class="flex items-center w-full p-2 text-base text-gray-900 transition duration-75 rounded-lg group hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700"
                    aria-controls="dropdown-example" data-collapse-toggle="dropdown-example">
                    <x-heroicon-s-calendar-date-range
                        class="w-7 h-7 text-gray-500 transition duration-75 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white" />
                    <span class="ms-3 text-gray-500 dark:text-gray-400 group-hover:text-gray-900">Timeframe</span>
                </button>
                {{-- <ul id="dropdown-example"
                    class="py-2 space-y-2 ml-1
                    {{ Request::routeIs('set-timeframe', 'edit-timeframe') ? '' : 'hidden' }}">
                    <li>
                        <a href="{{ route('set-timeframe') }}"
                            class="flex items-center w-full p-2 text-gray-500 transition duration-75 rounded-lg pl-11 group hover:text-gray-900 dark:text-white dark:hover:bg-gray-700
                            {{ Request::routeIs('set-timeframe') ? 'bg-blue-100' : '' }}">
                            Set Timeframe
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('edit-timeframe') }}"
                            class="flex items-center w-full p-2 text-gray-500 transition duration-75 rounded-lg pl-11 group hover:text-gray-900 dark:text-white dark:hover:bg-gray-700
                            {{ Request::routeIs('edit-timeframe') ? 'bg-blue-100' : '' }}">
                            Edit Timeframe
                        </a>
                    </li>
                </ul>
            </li>
            <li> --}}
                @php
                    // Retrieve the current user's role, or set it to an empty string if unauthenticated
$role = Auth::user()->role ?? '';
                @endphp

                @if (in_array($role, ['student', 'lecturer']))
                    <!-- If role is 'student' OR 'lecturer' -->
                    <a href="{{ route('lecturer-quota-list') }}"
                        class="flex items-center p-2 text-gray-900 rounded-lg
                               dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group">
                        <x-heroicon-c-list-bullet
                            class="w-7 h-7 text-gray-500 transition duration-75
                                   dark:text-gray-400 group-hover:text-gray-900
                                   dark:group-hover:text-white" />
                        <span class="ms-3 text-gray-500 dark:text-gray-400 group-hover:text-gray-900">
                            Quota
                        </span>
                    </a>
                @elseif ($role === 'coordinator')
                    <!-- If role is 'coordinator' -->
                    <a href="{{ route('manage-lecturer-quota') }}"
                        class="flex items-center p-2 text-gray-900 rounded-lg
                               dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group {{ Request::routeIs('manage-lecturer-quota') ? 'bg-blue-100' : '' }}">
                        <x-heroicon-c-list-bullet
                            class="w-7 h-7 text-gray-500 transition duration-75
                                   dark:text-gray-400 group-hover:text-gray-900
                                   dark:group-hover:text-white" />
                        <span class="ms-3 text-gray-500 dark:text-gray-400 group-hover:text-gray-900">
                            Quota
                        </span>
                    </a>
                @endif
            </li>
            <li>
                <form action="{{ route('auth.logout') }}" method="POST">
                    @csrf
                    <button type="submit"
                        class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group">
                        <x-tabler-logout
                            class="w-7 h-7 text-gray-500 transition duration-75 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white" />
                        <span class="ms-3 text-gray-500 dark:text-gray-400 group-hover:text-gray-900">Logout</span>
                    </button>
                </form>
            </li>
        </ul>
    </div>
</aside>
