<?php

namespace App\Http\Controllers\ManageUser;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use App\Models\User;
use App\Models\Program;
use App\Models\ResearchGroup;

class ManageUserController extends Controller
{
    // Display the Coordinator User List
    public function displayUserList()
    {
        // Get users from the database
        $users = User::all();
    
        // Convert the array into a collection and paginate
        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        $perPage = 4;
        $items = collect($users);
        $currentPageItems = $items->slice(($currentPage - 1) * $perPage, $perPage)->all();
    
        $paginatedItems = new LengthAwarePaginator(
            $currentPageItems,
            $items->count(),
            $perPage,
            $currentPage,
            ['path' => LengthAwarePaginator::resolveCurrentPath()]
        );

        // Get all research group
        $researchGroup = ResearchGroup::all();
        return view('ManageUser.user-list', [
            'users' => $paginatedItems,
            'researchGroup' => $researchGroup
        ]);
    }

    // Create user with bulk data by using csv file
    public function createUserBulk(Request $request)
    {
        Log::info('Bulk User Creation Request Received');
        Log::info('Request Data: ' . json_encode($request->all()));

        // Validate the request is csv or excel file
        if (!$request->hasFile('dropzone-file') || !$request->file('dropzone-file')->isValid()) {
            return redirect()->route('admin.user-list')->with('error', 'Invalid file uploaded. Please upload a valid CSV file.');
        }

        Log::info('Bulk User Creation Request: ' . $request->file('dropzone-file')->getClientOriginalName());
    
        // Use getRealPath() to get the path
        $realPath = $request->file('dropzone-file')->getRealPath();

        if (!$realPath) {
            Log::error('Failed to retrieve the real path of the uploaded file.');
            return redirect()->route('admin.user-list')->with('error', 'Failed to process the uploaded file.');
        }

        try {
            // Get the file
            $file = fopen($realPath, 'r');
    
            $users = [];
            $row = 0;
            while (($data = fgetcsv($file)) !== false) {
                $row++;

                // Skip the header row (row 1)
                if ($row === 1) {
                    continue;
                }

                // Ensure valid data format
                if (count($data) < 4) {
                    fclose($file);
                    return redirect()->route('user-list')->with('error', 'Invalid CSV format. Each row must have name, email, program_id, and role.');
                }

                $programId = (int) $data[3];
                // Check if the program_id exists
                if (!Program::where('id', $programId)->exists()) {
                    fclose($file);
                    return redirect()->route('admin.user-list')->with('error', "Program ID $programId does not exist.");
                }
    
                $users[] = [
                    'name' => $data[0],
                    'email' => $data[1],
                    'password' => bcrypt($data[2]), // Default password
                    'program_id' => $programId,
                    'first_login' => 1,
                    'role' => $data[4],
                    'created_at' => now(),
                ];
            }
            fclose($file);
    
            // Insert users
            User::insert($users);
    
            return redirect()->route('admin.user-list')->with('success', 'Users created successfully.');
        } catch (\Exception $e) {
            // Log error for debugging
            Log::error('Bulk User Creation Error: ' . $e->getMessage());
            return redirect()->route('admin.user-list')->with('error', 'Failed to create users. Please try again.');
        }
    }

    // Update Research Group
    public function updateResearchGroup(Request $request)
    {
        Log::info('Update Research Group Request Received');

        $userId = $request->input('user_id');
        $researchGroupId = $request->input('research-group');

        Log::info('User ID: ' . $userId);
        Log::info('Research Group ID: ' . $researchGroupId);
    
        // Find the user
        $user = User::find($userId);
        if (!$user) {
            return redirect()->route('admin.user-list')->with('error', "User not found.");
        }
    
        // Find the research group
        $researchGroup = ResearchGroup::find($researchGroupId);
        if (!$researchGroup) {
            return redirect()->route('admin.user-list')->with('error', "Research group not found.");
        }
    
        // Update the user's research group
        $user->research_group_id = $researchGroupId;
        $user->save();
    
        return redirect()->route('admin.user-list')->with('success', "Research group had been updated successfully.");
    }

    // Display the Coordinator User Report
    public function displayUserReport()
    {
        $users = [
            ['no' => '01.', 'name' => 'Test User 1', 'email' => 'test1@domain.com', 'program' => 'Software', 'role' => 'Student', 'action' => 'Assign To'],
            ['no' => '02.', 'name' => 'Test User 2', 'email' => 'test2@domain.com', 'program' => 'Networking', 'role' => 'Student', 'action' => 'Assign To'],
            ['no' => '03.', 'name' => 'Test User 3', 'email' => 'test3@domain.com', 'program' => 'Software', 'role' => 'Lecturer', 'action' => 'Assigned'],
            ['no' => '04.', 'name' => 'Test User 4', 'email' => 'test4@domain.com', 'program' => 'Engineering', 'role' => 'Student', 'action' => 'Assign To'],
            ['no' => '05.', 'name' => 'Test User 5', 'email' => 'test5@domain.com', 'program' => 'Science', 'role' => 'Lecturer', 'action' => 'Assigned'],
            // Add more static data as needed
        ];
    
        // Convert the array into a collection and paginate
        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        $perPage = 5;
        $items = collect($users);
        $currentPageItems = $items->slice(($currentPage - 1) * $perPage, $perPage)->all();
    
        $paginatedItems = new LengthAwarePaginator(
            $currentPageItems,
            $items->count(),
            $perPage,
            $currentPage,
            ['path' => LengthAwarePaginator::resolveCurrentPath()]
        );

        return view('ManageUser.user-report', ['users' => $paginatedItems]);
    }

    // Filter User Report
    public function filterData(Request $request)
    {
        $program = $request->input('program');
        $year = $request->input('year');
        $perPage = 5; // Items per page
        $page = $request->input('page', 1); // Current page, default to 1
    
        // Simulate data (Replace this with your actual data source)
        $allUsers = collect([
            ['no' => '01.', 'name' => 'Test User 1', 'email' => 'test1@domain.com', 'program' => 'BCS', 'role' => 'Student', 'year' => '2021'],
            ['no' => '02.', 'name' => 'Test User 2', 'email' => 'test2@domain.com', 'program' => 'BCN', 'role' => 'Lecturer', 'year' => '2020'],
            ['no' => '03.', 'name' => 'Test User 3', 'email' => 'test3@domain.com', 'program' => 'BCE', 'role' => 'Student', 'year' => '2021'],
            ['no' => '04.', 'name' => 'Test User 4', 'email' => 'test4@domain.com', 'program' => 'BCS', 'role' => 'Student',  'year' => '2020'],
            ['no' => '05.', 'name' => 'Test User 5', 'email' => 'test5@domain.com', 'program' => 'BCN', 'role' => 'Lecturer', 'year' => '2021'],
        ]);
    
        // Filter data
        $filteredUsers = $allUsers->filter(function ($user) use ($program, $year) {
            $programMatch = !$program || $user['program'] === $program;
            $yearMatch = !$year || $user['year'] === $year; // Modify if 'year' exists in your data
            return $programMatch && $yearMatch;
        });
    
        // Paginate filtered data
        $paginatedUsers = new LengthAwarePaginator(
            $filteredUsers->forPage($page, $perPage)->values()->toArray(), // Use values() to reindex array
            $filteredUsers->count(),
            $perPage,
            $page,
            ['path' => url('/admin/filter-data')]
        );
    
        $cards = [
            'totalUsers' => $filteredUsers->count(),
            'totalStudents' => $filteredUsers->where('role', 'Student')->count(),
            'totalLecturers' => $filteredUsers->where('role', 'Lecturer')->count(),
        ];
    
        return response()->json([
            'cards' => $cards,
            'users' => $paginatedUsers->items(), // Ensure items() returns an array
            'pagination' => [
                'current_page' => $paginatedUsers->currentPage(),
                'last_page' => $paginatedUsers->lastPage(),
                'per_page' => $paginatedUsers->perPage(),
                'total' => $paginatedUsers->total(),
            ],
        ]);
    }
    
    
    
}
