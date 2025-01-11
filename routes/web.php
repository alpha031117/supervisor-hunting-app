<?php

use App\Http\Controllers\ManageTimeframeAndQuota\QuotaController;
use App\Http\Controllers\ManageUser\AuthenticatedSessionController;
use App\Http\Controllers\ManageUser\ManageUserController;
use App\Http\Controllers\ManageTimeframeAndQuota\TimeframeController;
use App\Http\Controllers\ManageTitle\TitleController;
use Illuminate\Support\Facades\Route;

// Create route here
Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::middleware('auth')->group(function () {
    // Reset Password
    Route::get('/reset-password', [AuthenticatedSessionController::class, 'resetPassword'])
        ->name('auth.reset-password');

    // Confirm Password
    Route::post('/store-session', [AuthenticatedSessionController::class, 'store_session'])
        ->name('auth.store-session');

    Route::get('/confirm-password', [AuthenticatedSessionController::class, 'retypePassword'])
        ->name('auth.confirm-password');

    Route::post('/update-password', [AuthenticatedSessionController::class, 'updatePassword'])
        ->name('auth.update-password');

    // User Report
    Route::get('/user-report', [ManageUserController::class, 'displayUserReport'])
        ->name('admin.user-report');

    // Success Reset Password
    Route::get('/success-reset-password', function () {
        return view('ManageUser.reset-successful');
    })->name('auth.success-reset-password');

    // Coordinator
    Route::middleware('checkrole:coordinator')->group(function () {
        // Dashboard
        Route::get('/coordinator-dashboard', function () {
            return view('FYPCoordinatorDashboard');
        })->name('coordinator.dashboard');

        // User List
        Route::get('/user-list', [ManageUserController::class, 'displayUserList'])
            ->name('admin.user-list');

        // Create User Bulk
        Route::post('/create-user-bulk', [ManageUserController::class, 'createUserBulk'])
            ->name('admin.create-user-bulk');

        // Update Research Group
        Route::post('/update-research-group', [ManageUserController::class, 'updateResearchGroup'])
            ->name('admin.update-research-group');

        // User Report
        Route::get('/user-report', [ManageUserController::class, 'displayUserReport'])
            ->name('admin.user-report');

        Route::post('/admin/filter-data', [ManageUserController::class, 'filterData'])
            ->name('admin.filter-data');

        Route::post('/admin/filter-data-all', [ManageUserController::class, 'displayFilteredUser'])
            ->name('admin.filter-data-all');
    });

    // Lecturer
    Route::middleware('checkrole:lecturer')->get('/lecturer-dashboard', function () {
        return view('lecturerDashboard');
    })->name('lecturer.dashboard');

    // Student
    Route::middleware('checkrole:student')->get('/student-dashboard', function () {
        return view('studentDashboard');
    })->name('student.dashboard');
});

// Login
Route::get('/login', [AuthenticatedSessionController::class, 'create'])
    ->name('login');

// Authenticate
Route::post('login', [AuthenticatedSessionController::class, 'store'])
    ->name('auth.login');

// Logout
Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])
    ->name('auth.logout');

Route::middleware('auth')->group(function () {
    // Reset Password
    Route::get('/reset-password', [AuthenticatedSessionController::class, 'resetPassword'])
        ->name('auth.reset-password');

    // Confirm Password
    Route::post('/store-session', [AuthenticatedSessionController::class, 'store_session'])
        ->name('auth.store-session');
    Route::get('/confirm-password', [AuthenticatedSessionController::class, 'retypePassword'])
        ->name('auth.confirm-password');
    Route::post('/update-password', [AuthenticatedSessionController::class, 'updatePassword'])
        ->name('auth.update-password');

    // Success Reset Password
    Route::get('/success-reset-password', function () {
        return view('ManageUser.reset-successful');
    })
        ->name('auth.success-reset-password');
});



//Apply Title Module
//Student

Route::get('/ProposalList', [TitleController::class, 'accessdb']);
