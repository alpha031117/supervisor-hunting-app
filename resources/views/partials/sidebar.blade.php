@php
    $role = Auth::user()->role ?? '';
@endphp

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
            @if ($role === 'student')
                <li>
                    <a href="{{ route('student.dashboard') }}"
                        class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group">
                        <x-heroicon-s-home
                            class="w-7 h-7 text-gray-500 transition duration-75 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white" />
                        <span class="ms-3 text-gray-500 dark:text-gray-400 group-hover:text-gray-900">Dashboard</span>
                    </a>
                </li>
            @elseif ($role === 'lecturer')
                <li>
                    <a href="{{ route('lecturer.dashboard') }}"
                        class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group">
                        <x-heroicon-s-home
                            class="w-7 h-7 text-gray-500 transition duration-75 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white" />
                        <span class="ms-3 text-gray-500 dark:text-gray-400 group-hover:text-gray-900">Dashboard</span>
                    </a>
                </li>
            @elseif ($role === 'coordinator')
                <li>
                    <a href="{{ route('coordinator.dashboard') }}"
                        class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group">
                        <x-heroicon-s-home
                            class="w-7 h-7 text-gray-500 transition duration-75 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white" />
                        <span class="ms-3 text-gray-500 dark:text-gray-400 group-hover:text-gray-900">Dashboard</span>
                    </a>
                </li>
            @endif

            @if ($role === 'coordinator')
                <li>
                    <a href="{{ route('admin.user-list') }}"
                        class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group">
                        <x-heroicon-s-users
                            class="w-7 h-7 text-gray-500 transition duration-75 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white" />
                        <span class="ms-3 text-gray-500 dark:text-gray-400 group-hover:text-gray-900">User</span>
                    </a>
                </li>
            @endif

            @if ($role === 'student')
                <li>
                    <a href="#"
                        class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group">
                        <x-iconoir-graduation-cap-solid
                            class="w-7 h-7 text-gray-500 transition duration-75 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white" />
                        <span class="ms-3 text-gray-500 dark:text-gray-400 group-hover:text-gray-900">Topic
                            Approval</span>
                    </a>
                </li>
            @elseif ($role === 'lecturer')
                <li>
                    <a href="#"
                        class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group">
                        <x-iconoir-graduation-cap-solid
                            class="w-7 h-7 text-gray-500 transition duration-75 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white" />
                        <span class="ms-3 text-gray-500 dark:text-gray-400 group-hover:text-gray-900">Topic
                            Approval</span>
                    </a>
                </li>
            @endif

            @if (in_array($role, ['student', 'lecturer']))
                {{-- Drop Down List Start Here --}}
                @php
                    // Determine if dropdown should be open based on active routes
                    $isDropdownOpen =
                        Request::routeIs('search') ||
                        Request::routeIs('appointments.myAppointments') ||
                        Request::routeIs('schedule.upload.form') ||
                        Request::routeIs('lecturer.appointments');
                @endphp

                <button type="button"
                    class="flex items-center w-full p-2 text-base text-gray-900 transition duration-75 rounded-lg group hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700"
                    aria-controls="dropdown-example" data-collapse-toggle="dropdown-example">
                    <x-mdi-timetable
                        class="w-7 h-7 text-gray-500 transition duration-75 dark:text-gray-400 group-hover:text-gray-500 dark:group-hover:text-white" />
                    <span class="ms-3 text-gray-500 dark:text-gray-400 group-hover:text-gray-900">Timetable</span>
                </button>
                <ul id="dropdown-example" class="{{ $isDropdownOpen ? 'block' : 'hidden' }} py-2 space-y-2 ml-1">
                    @if ($role === 'student')
                        <li>
                            <a href="{{ route('search') }}"
                                class="flex items-center w-full p-2 text-gray-500 transition duration-75 rounded-lg pl-11 group hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700 {{ Request::routeIs('search') ? 'bg-blue-100' : '' }}">
                                Search Lecturer
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('appointments.myAppointments') }}"
                                class="flex items-center w-full p-2 text-gray-500 transition duration-75 rounded-lg pl-11 group hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700 {{ Request::routeIs('appointments.myAppointments') ? 'bg-blue-100' : '' }}">
                                My Appointment
                            </a>
                        </li>
                    @elseif ($role === 'lecturer')
                        <li>
                            <a href="{{ route('schedule.upload.form') }}"
                                class="flex items-center w-full p-2 text-gray-500 transition duration-75 rounded-lg pl-11 group hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700 {{ Request::routeIs('schedule.upload.form') ? 'bg-blue-100' : '' }}">
                                Timetable
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('lecturer.appointments') }}"
                                class="flex items-center w-full p-2 text-gray-500 transition duration-75 rounded-lg pl-11 group hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700 {{ Request::routeIs('lecturer.appointments') ? 'bg-blue-100' : '' }}">
                                Appointment Request
                            </a>
                        </li>
                    @endif
                </ul>
                {{-- Drop Down List End Here --}}

            @endif

            @if ($role === 'coordinator')
                {{-- Timeframe Drop Down List Start Here --}}
                @php
                    // Determine if Timeframe dropdown should be open based on active routes
                    $isTimeframeOpen = Request::routeIs('set-timeframe') || Request::routeIs('edit-timeframe');
                @endphp

                <button type="button"
                    class="flex items-center w-full p-2 text-base text-gray-900 transition duration-75 rounded-lg group hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700"
                    aria-controls="timeframe-dropdown" data-collapse-toggle="timeframe-dropdown">
                    <x-heroicon-s-calendar-date-range
                        class="w-7 h-7 text-gray-500 transition duration-75 dark:text-gray-400 group-hover:text-gray-500 dark:group-hover:text-white" />
                    <span class="ms-3 text-gray-500 dark:text-gray-400 group-hover:text-gray-900">Timeframe</span>
                </button>
                <ul id="timeframe-dropdown" class="{{ $isTimeframeOpen ? 'block' : 'hidden' }} py-2 space-y-2 ml-1">
                    <li>
                        <a href="{{ route('set-timeframe') }}"
                            class="flex items-center w-full p-2 text-gray-500 transition duration-75 rounded-lg pl-11 group hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700 {{ Request::routeIs('set-timeframe') ? 'bg-blue-100' : '' }}">
                            Set Timeframe
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('edit-timeframe') }}"
                            class="flex items-center w-full p-2 text-gray-500 transition duration-75 rounded-lg pl-11 group hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700 {{ Request::routeIs('edit-timeframe') ? 'bg-blue-100' : '' }}">
                            Edit Timeframe
                        </a>
                    </li>
                </ul>
                {{-- Timeframe Drop Down List End Here --}}
            @endif

            <li>
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
                        class="flex items-center w-full p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group">
                        <x-tabler-logout
                            class="w-7 h-7 text-gray-500 transition duration-75 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white" />
                        <span class="ms-3 text-gray-500 dark:text-gray-400 group-hover:text-gray-900">Logout</span>
                    </button>
                </form>
            </li>
        </ul>
    </div>
</aside>
