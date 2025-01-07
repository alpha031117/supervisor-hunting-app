<?php

namespace App\Http\Controllers\ManageUser;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Log;
use App\Models\User;
use App\Models\LecturerQuota;
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

    // Create User Bulk
    public function createUserBulk(Request $request)
    {
        Log::info('Bulk User Creation Request Received');
        Log::info('Request Data: ' . json_encode($request->all()));
    
        if (!$request->hasFile('dropzone-file') || !$request->file('dropzone-file')->isValid()) {
            return redirect()->route('admin.user-list')->with('error', 'Invalid file uploaded. Please upload a valid CSV file.');
        }
    
        $realPath = $request->file('dropzone-file')->getRealPath();
        if (!$realPath) {
            Log::error('Failed to retrieve the real path of the uploaded file.');
            return redirect()->route('admin.user-list')->with('error', 'Failed to process the uploaded file.');
        }
    
        try {
            $file = fopen($realPath, 'r');
            $users = [];
            $lecturerEmails = []; // Collect lecturer emails for quota processing
            $row = 0;
    
            while (($data = fgetcsv($file)) !== false) {
                $row++;
                if ($row === 1) {
                    if ($data[0] === 'name' && $data[1] === 'email' && $data[2] === 'password' && $data[3] === 'pi/rg' && $data[4] === 'role' && $data[5] === 'academic_year') {
                        Log::info('CSV header format is valid.');
                        continue;
                    } else {
                        fclose($file);
                        return redirect()->route('admin.user-list')->with('error', 'Invalid CSV header format. Each row must have name, email, pi/rg, role and academic_year.');
                    }
                }
    
                $role = $data[4];
                if ($role === 'student') {
                    $programId = (int) $data[3];
                    if (!Program::where('id', $programId)->exists()) {
                        fclose($file);
                        return redirect()->route('admin.user-list')->with('error', "Program ID $programId does not exist.");
                    }
    
                    $users[] = [
                        'name' => $data[0],
                        'email' => $data[1],
                        'password' => bcrypt($data[2]),
                        'program_id' => $programId,
                        'year' => $data[5],
                        'first_login' => 1,
                        'role' => $role,
                        'created_at' => now(),
                    ];
                } else {
                    $researchGroupId = (int) $data[3];
                    if (!Program::where('id', $researchGroupId)->exists()) {
                        fclose($file);
                        return redirect()->route('admin.user-list')->with('error', "Research Group ID $researchGroupId does not exist.");
                    }
    
                    $users[] = [
                        'name' => $data[0],
                        'email' => $data[1],
                        'password' => bcrypt($data[2]),
                        'research_group_id' => $researchGroupId,
                        'year' => $data[5],
                        'first_login' => 1,
                        'role' => $role,
                        'created_at' => now(),
                    ];
    
                    $lecturerEmails[] = [
                        'email' => $data[1],
                        'semester' => 'Semester' . $data[5],
                    ];
                }
            }
            fclose($file);
    
            // Insert users into the database
            User::insert($users);
    
            // Process lecturer quotas
            $lecturer_quota = [];
            foreach ($lecturerEmails as $lecturerData) {
                $lecturer = User::where('email', $lecturerData['email'])->first();
                if ($lecturer) {
                    $lecturer_quota[] = [
                        'semester' => $lecturerData['semester'],
                        'lecturer_id' => $lecturer->id,
                        'total_quota' => 10,
                        'remaining_quota' => 10,
                        'created_at' => now(),
                    ];
                }
            }
    
            if (!empty($lecturer_quota)) {
                LecturerQuota::insert($lecturer_quota);
            }
    
            return redirect()->route('admin.user-list')->with('success', 'Users created successfully.');
        } catch (\Exception $e) {
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
        // Get all programs
        $programs = Program::all();

        // Get academic year
        $currentYear = now()->year;
        $startYear = $currentYear;
        $endYear = $currentYear - 4; // Adjust the range as needed
    
        $academicYears = [];
        for ($year = $startYear; $year > $endYear; $year--) {
            $academicYears[] = ($year - 1) . '/' . $year;
        }

        return view('ManageUser.user-report', [
            'programs' => $programs,
            'academicYears' => $academicYears,
        ]);
    }

    // Display all filtered user in print view
    public function displayFilteredUser(Request $request)
    {
        $program = $request->input('program');
        $year = $request->input('year');
    
        $allUsers = User::with('program')->get();
    
        // Filter data
        $filteredUsers = $allUsers->filter(function ($user) use ($program, $year) {
            $programMatch = !$program || $user->program->description === $program;
            $yearMatch = !$year || $user['year'] === $year; // Modify if 'year' exists in your data
            return $programMatch && $yearMatch;
        });

        // Get specific program based on the program id
        $program = Program::where('description', $program)->first();

        return response()->json(['users' => $filteredUsers, 'program' => $program]);
    }

    // Filter User Report
    public function filterData(Request $request)
    {
        $program = $request->input('program');
        $year = $request->input('year');
        $perPage = 3; // Items per page
        $page = $request->input('page', 1); // Current page, default to 1
    
        $allUsers = User::with('program')->get();
    
        // Filter data
        $filteredUsers = $allUsers->filter(function ($user) use ($program, $year) {
            $programMatch = !$program || $user->program->description === $program;
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
            'totalStudents' => $filteredUsers->where('role', 'student')->count(),
            'totalLecturers' => $filteredUsers->where('role', 'lecturer')->count(),
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
