@extends('layouts.master')

@section('page', 'Manage User')

@section('breadcrumbs')
    <a href="{{ route('coordinator.dashboard')}}" class="hover:text-blue-600"><li>FYP Hunt</li></a>
    <li>/</li>
    <a href="{{ route('admin.user-list')}}" class="text-blue-600"><li>Manage User</li></a>
@endsection

@section('content')
    @if (session('success') || session('error'))
        @if (session('success'))
            <div id="alert-1" class="flex items-center p-4 mb-4 text-green-500 rounded-lg bg-green-50" role="alert">
        @elseif (session('error'))
            <div id="alert-1" class="flex items-center p-4 mb-4 text-red-500 rounded-lg bg-red-50" role="alert">
        @endif
            <svg class="flex-shrink-0 w-4 h-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 1 1 1 1v4h1a1 1 0 0 1 0 2Z"/>
            </svg>
            <span class="sr-only">
                @if (session('success'))
                    Success
                @elseif (session('error'))
                    Error
                @endif
            </span>
            <div class="ms-3 text-sm font-medium">
                @if (session('success'))
                    <p>{{ session('success') }}</p>
                @elseif (session('error'))
                    <p>{{ session('error') }}</p>
                @endif
            </div>
            @if (session('error'))
                <button type="button" class="ms-auto -mx-1.5 -my-1.5 bg-red-50 text-red-500 rounded-lg focus:ring-2 focus:ring-red-400 p-1.5 hover:bg-red-200 inline-flex items-center justify-center h-8 w-8" data-dismiss-target="#alert-1" aria-label="Close">
                    <span class="sr-only">Close</span>
                    <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                    </svg>
                </button>
            @else
                <button type="button" class="ms-auto -mx-1.5 -my-1.5 bg-green-50 text-green-500 rounded-lg focus:ring-2 focus:ring-green-400 p-1.5 hover:bg-green-200 inline-flex items-center justify-center h-8 w-8" data-dismiss-target="#alert-1" aria-label="Close">
                    <span class="sr-only">Close</span>
                    <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                    </svg>
                </button>
            @endif
        </div>
    @endif

    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-semibold">List of Registered Users</h1>
        <div class="space-x-4">
            <a href="{{ route('admin.user-report')}}">
                <button class="border border-blue-500 text-blue-500 px-4 py-2 rounded-lg hover:bg-blue-100">
                    Generate Report
                </button>
            </a>
            <button data-modal-target="create-modal" data-modal-toggle="create-modal" class="border border-blue-500 text-blue-500 px-4 py-2 rounded-lg hover:bg-blue-100">
                Register New User
            </button>
        </div>
    </div>

    <!-- Tabs -->
    <div class="flex space-x-6 mb-4 border-b">
        <button id="all-tab" onclick="filterTable('all')" class="tab-btn text-blue-500 font-medium border-b-2 border-blue-500 px-4 py-2">
            All
        </button>
        <button id="coordinator-tab" onclick="filterTable('coordinator')" class="tab-btn text-gray-500 hover:text-blue-500 px-4 py-2">
            Coordinator
        </button>
        <button id="students-tab" onclick="filterTable('student')" class="tab-btn text-gray-500 hover:text-blue-500 px-4 py-2">
            Students
        </button>
        <button id="lecturers-tab" onclick="filterTable('lecturer')" class="tab-btn text-gray-500 hover:text-blue-500 px-4 py-2">
            Lecturers
        </button>
    </div>

    <!-- Table -->
    <div class="overflow-x-auto">
        <table id="user-table" class="table-auto w-full text-left">
            <thead class="bg-gray-100">
                <tr>
                    <th class="px-4 py-2 text-sm font-medium text-gray-700">No</th>
                    <th class="px-4 py-2 text-sm font-medium text-gray-700">Name</th>
                    <th class="px-4 py-2 text-sm font-medium text-gray-700">Email</th>
                    <th class="px-4 py-2 text-sm font-medium text-gray-700">Program</th>
                    <th class="px-4 py-2 text-sm font-medium text-gray-700">Academic Year</th>
                    <th class="px-4 py-2 text-sm font-medium text-gray-700">Role</th>
                    <th class="px-4 py-2 text-sm font-medium text-gray-700 text-center justify-center">Research Group</th>
                </tr>
            </thead>
            <tbody class="space-y-4">
                @foreach ($users as $user)
                <tr class="border-b h-16" data-role="{{ $user->role }}">
                    <td class="px-4 py-2 text-sm text-gray-700">{{ $loop->iteration }}</td>
                    <td class="px-4 py-2 text-sm text-gray-700">{{ $user->name }}</td>
                    <td class="px-4 py-2 text-sm text-gray-700">{{ $user->email }}</td>
                    <td class="px-4 py-2 text-sm text-gray-700">{{ $user->program->name }}</td>
                    <td class="px-4 py-2 text-sm text-gray-700">{{ $user->year }}</td>
                    <td class="px-4 py-2 text-sm text-gray-700">{{ $user->role }}</td>
                    <td class="px-4 py-4 flex items-center justify-center">
                        @if ($user->role != 'lecturer')
                            N/A
                        @elseif ($user->researchGroup)
                            <button data-modal-target="assign-modal" data-modal-toggle="assign-modal" 
                            data-research-group-id="{{ $user->researchGroup->id }}"
                            data-user-id="{{ $user->id }}"
                            class="text-green-500 border border-green-500 px-4 py-1 rounded-lg hover:bg-green-100">
                                {{ $user->researchGroup->group_name }}
                            </button>
                        @else
                            <button data-modal-target="assign-modal" data-modal-toggle="assign-modal" 
                            data-research-group-id="null"
                            data-user-id="{{ $user->id }}"
                            class="text-blue-500 border border-blue-500 px-4 py-1 rounded-lg hover:bg-blue-100">
                                Not Assigned
                            </button>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Pagination Links -->
    <div class="mt-6">
        {{ $users->links('vendor.pagination.tailwind') }}
    </div>
  
  <!-- Main modal -->
  {{-- Create User Modal --}}
  <div id="create-modal" tabindex="-1" aria-hidden="true" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
      <div class="relative p-4 w-full max-w-full max-h-full">
          <!-- Modal content -->
          <div class="relative bg-white rounded-lg shadow">
              <!-- Modal header -->
              <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t">
                  <h3 class="text-lg font-semibold text-gray-900">
                      Bulk Register Users
                  </h3>
                  <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center" data-modal-toggle="create-modal">
                      <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                          <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                      </svg>
                      <span class="sr-only">Close modal</span>
                  </button>
              </div>
              <!-- Modal body -->
              <form class="p-4 md:p-5" action="{{ route('admin.create-user-bulk') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="grid gap-4 mb-4 grid-cols-2">
                        {{-- <div class="col-span-2">
                                <label for="category" class="block mb-2 text-sm font-medium text-gray-900">Category</label>
                                <select id="category" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5">
                                    <option selected="">Type of Users</option>
                                    <option value="FYPCoordinator">FYP Coordinator</option>
                                    <option value="Lecturer">Lecturer</option>
                                    <option value="Student">Student</option>
                                </select>
                        </div> --}}
                        <div class="col-span-2">
                            <div id="dropzone-container" class="flex items-center justify-center w-full">
                                <label for="dropzone-file" class="flex flex-col items-center justify-center w-full h-64 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50">
                                    <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                        <svg class="w-8 h-8 mb-4 text-gray-500" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 16">
                                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 13h3a3 3 0 0 0 0-6h-.025A5.56 5.56 0 0 0 16 6.5 5.5 5.5 0 0 0 5.207 5.021C5.137 5.017 5.071 5 5 5a4 4 0 0 0 0 8h2.167M10 15V6m0 0L8 8m2-2 2 2"/>
                                        </svg>
                                        <p class="mb-2 text-sm text-gray-500"><span class="font-semibold">Click to upload</span> or drag and drop</p>
                                        <p class="text-xs text-gray-500">.CSV or Excel Files only</p>
                                    </div>
                                    <input id="dropzone-file" name="dropzone-file" type="file" accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel" class="hidden" />
                                </label>
                            </div> 
                            <!-- Upload Status -->
                            <div id="upload-status" class="items-center p-4 mb-4 text-sm text-green-800 border border-green-300 rounded-lg bg-green-50 hidden" role="alert">
                                <svg class="flex-shrink-0 inline w-4 h-4 me-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z"/>
                                </svg>
                                <span class="sr-only">Info</span>
                                <span class="font-semibold" id="status-message">File uploaded successfully!</span>
                                <div class="mt-3">
                                    <p id="file-info"></p>
                                </div>
                            </div>

                        </div>
                    </div>
                    <button type="submit" class="text-white inline-flex items-center bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center">
                        Create Users
                    </button>
              </form>
          </div>
      </div>
  </div> 
  
  {{-- Assign Lecturer Research Group Modal --}}
    <div id="assign-modal" tabindex="-1" aria-hidden="true" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
        <div class="relative p-4 w-full max-w-full max-h-full">
            <!-- Modal content -->
            <div class="relative bg-white rounded-lg shadow">
                <!-- Modal header -->
                <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t">
                    <h3 class="text-lg font-semibold text-gray-900">
                        Assign Research Group
                    </h3>
                    <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center" data-modal-toggle="assign-modal">
                        <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                        </svg>
                        <span class="sr-only">Close modal</span>
                    </button>
                </div>
                <!-- Modal body -->
                <form class="p-4 md:p-5" action="{{ route('admin.update-research-group') }}" method="POST">
                    @csrf
                    <div class="grid gap-4 mb-4 grid-cols-2">
                        <div class="col-span-2">
                            <label for="research-group" class="block mb-2 text-sm font-medium text-gray-900">Research Group</label>
                            <select id="research-group" name="research-group" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5">
                                <option value="" disabled>Select Research Group</option>
                                @foreach ($researchGroup as $research)
                                    <option value="{{ $research->id }}">{{ $research->group_name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <input type="hidden" name="user_id" id="user-id">
                    <button type="submit" class="text-white inline-flex items-center bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center">
                        Assign Research Group
                    </button>
                </form>   
            </div>
        </div>
    </div>

    <!-- JavaScript -->
    <script>
        // Filter Table Script
        function filterTable(role) {
            const rows = document.querySelectorAll('#user-table tbody tr');
            const tabs = document.querySelectorAll('.tab-btn');

            rows.forEach(row => {
                if (role === 'all' || row.getAttribute('data-role') === role) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });

            tabs.forEach(tab => {
                tab.classList.remove('text-blue-500', 'font-medium', 'border-b-2', 'border-blue-500');
                tab.classList.add('text-gray-500');
            });

            document.getElementById(`${role.toLowerCase()}-tab`).classList.add('text-blue-500', 'font-medium', 'border-b-2', 'border-blue-500');
        }

        document.addEventListener('DOMContentLoaded', () => {
            filterTable('all');
            const buttons = document.querySelectorAll('[data-modal-toggle="assign-modal"]');
            const modalSelect = document.getElementById('research-group');

            buttons.forEach(button => {
                button.addEventListener('click', () => {
                    const researchGroupId = button.getAttribute('data-research-group-id');
                    const userId = button.getAttribute('data-user-id');

                    // Set the user id
                    document.getElementById('user-id').value = userId;

                    // Reset the select dropdown
                    modalSelect.value = '';

                    // Set the selected option if researchGroupId is provided
                    if (researchGroupId && researchGroupId !== 'null') {
                        modalSelect.value = researchGroupId;
                    }
                });
            });
        });

        // Modal Script
        const fileInput = document.getElementById('dropzone-file');
        const dropzoneContainer = document.getElementById('dropzone-container');
        const uploadStatus = document.getElementById('upload-status');
        const fileInfo = document.getElementById('file-info');
        const statusMessage = document.getElementById('status-message');

        fileInput.addEventListener('change', () => {
            const file = fileInput.files[0];
            if (file) {
                // Hide the dropzone
                dropzoneContainer.classList.add('hidden');

                // Display file name and size
                fileInfo.textContent = `File Selected: ${file.name} (${(file.size / 1024).toFixed(2)} KB)`;

                // Display success message
                statusMessage.textContent = 'File uploaded successfully!';

                // Show the upload status
                uploadStatus.classList.remove('hidden');
            }
        });
    </script>
@endsection
