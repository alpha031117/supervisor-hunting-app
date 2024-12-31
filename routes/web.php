<?php

use App\Http\Controllers\ManageUser\AuthenticatedSessionController;
use App\Http\Controllers\ManageUser\ManageUserController;
use Illuminate\Support\Facades\Route;

// Create route here
Route::get('/', function () {
    return view('welcome');
})->name('home');

// Login
Route::get('/login', [AuthenticatedSessionController::class, 'create'])
    ->middleware('guest')
    ->name('login');

// Reset Password
Route::get('/reset-password', [AuthenticatedSessionController::class, 'resetPassword'])
    ->middleware('guest')
    ->name('reset-password');

// Confirm Password
Route::post('/store-session', [AuthenticatedSessionController::class, 'store_session'])
    ->middleware('guest')
    ->name('store-session');
Route::get('/confirm-password', [AuthenticatedSessionController::class, 'retypePassword'])
    ->middleware('guest')
    ->name('confirm-password');

// Success Reset Password
Route::get('/success-reset-password', function () {
    return view('ManageUser.reset-successful');
})->middleware('guest')
    ->name('success-reset-password');

// User List
Route::get('/user-list', [ManageUserController::class, 'displayUserList'])
    // ->middleware('auth')
    ->name('admin.user-list');

// User Report
Route::get('/user-report', [ManageUserController::class, 'displayUserReport'])
    // ->middleware('auth')
    ->name('admin.user-report');
Route::post('/admin/filter-data', [ManageUserController::class, 'filterData'])->name('admin.filter-data');
