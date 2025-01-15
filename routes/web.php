<?php

use App\Http\Controllers\ManageTimeframeAndQuota\QuotaController;
use App\Http\Controllers\ManageUser\AuthenticatedSessionController;
use App\Http\Controllers\ManageUser\ManageUserController;
use App\Http\Controllers\ManageAppointment\ManageAppointmentController;
use App\Http\Controllers\ManageTimeframeAndQuota\TimeframeController;
use App\Http\Controllers\ManageTitle\ManageTitleController;
use App\Http\Controllers\ManageTitle\TitleController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

// Auth::routes(['verify' => true]);
Route::middleware(['auth:sanctum'])->group(function () {

    Route::get('/my-appointments', [ManageAppointmentController::class, 'listAppointments'])->name('appointments.myAppointments');
    Route::post('/applyappointment/{lecturer}', [ManageAppointmentController::class, 'create'])->name('applyappointment.create');
    Route::get('/applyappointment/{lecturer}', [ManageAppointmentController::class, 'showRequestForm'])->name('appointments.request');
    Route::post('/appointments', [ManageAppointmentController::class, 'store'])->name('appointments.store');
    Route::delete('/appointments/{id}', [ManageAppointmentController::class, 'cancelAppointment'])->name('appointments.cancel');
    Route::get('/appointments/{id}', [ManageAppointmentController::class, 'show'])->name('appointments.show');

    // Lecturer Routes
    Route::get('/lecturer/appointments', [ManageAppointmentController::class, 'lecturerAppointments'])->name('lecturer.appointments');
    Route::get('/response-appointment', [ManageAppointmentController::class, 'responseAppointment'])->name('lecturer.responseAppointment');
    Route::post('/lecturer/appointments/{id}/approve', [ManageAppointmentController::class, 'approveAppointment'])->name('approveAppointment');
    Route::post('/lecturer/appointments/{id}/reject', [ManageAppointmentController::class, 'rejectAppointment'])->name('rejectAppointment');

    // Upload Routes
    Route::get('/upload', [ManageAppointmentController::class, 'showUploadForm'])->name('schedule.upload.form');
    Route::post('/upload', [ManageAppointmentController::class, 'uploadSchedule'])->name('schedule.upload');

    Route::get('/search', [ManageAppointmentController::class, 'search'])->name('search');
});


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

        Route::get('/set-timeframe', [TimeframeController::class, 'setTimeframe'])
            ->name('set-timeframe');

        Route::post('/store-timeframe', [TimeframeController::class, 'storeTimeframe'])
            ->name('store-timeframe');

        Route::get('/edit-timeframe/{id?}', [TimeframeController::class, 'editTimeframe'])
            ->name('edit-timeframe');

        Route::post('/update-timeframe', [TimeframeController::class, 'updateTimeframe'])
            ->name('update-timeframe');

        Route::get('/manage-lecturer-quota', [QuotaController::class, 'manageLecturerQuota'])
            ->name('manage-lecturer-quota');

        Route::post('/save-lecturer-quota/{lecturer_id}', [QuotaController::class, 'saveQuota'])
            ->name('save-lecturer-quota');

        Route::get('/admin/quota-data', [QuotaController::class, 'getQuotaData'])
            ->name('lecturer-quota-report');

        Route::get('/lecturer-quota-report', [QuotaController::class, 'displayQuotaReport'])
            ->name('lecturer-quota-report');

        Route::delete('/delete-timeframe/{id}', [TimeframeController::class, 'deleteTimeframe'])
            ->name('delete-timeframe');
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
Route::get('/', [AuthenticatedSessionController::class, 'create'])
    ->name('login');

// Authenticate
Route::post('login', [AuthenticatedSessionController::class, 'store'])
    ->name('auth.login');

// Logout
Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])
    ->name('auth.logout');

// Route to display the lecturer quota list
Route::get('/lecturer-quota-list', [QuotaController::class, 'displayLecturerQuota']);

Route::get('/filter-lecturer-quota', [QuotaController::class, 'filterBySemester'])
    ->name('filter-lecturer-quota');
