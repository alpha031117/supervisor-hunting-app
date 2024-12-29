<?php

namespace App\Http\Controllers\ManageUser;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class ManageUserController extends Controller
{
    // Display the Coordinator User List
    public function displayUserList()
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
        $perPage = 3;
        $items = collect($users);
        $currentPageItems = $items->slice(($currentPage - 1) * $perPage, $perPage)->all();
    
        $paginatedItems = new LengthAwarePaginator(
            $currentPageItems,
            $items->count(),
            $perPage,
            $currentPage,
            ['path' => LengthAwarePaginator::resolveCurrentPath()]
        );

        return view('ManageUser.user-list', ['users' => $paginatedItems]);
    }

    // Display the Coordinator User Report
    public function displayUserReport()
    {
        return view('ManageUser.user-report');
    }
}
