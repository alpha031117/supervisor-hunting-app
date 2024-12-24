<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

// Create route here
Route::get('/', function () {
    return view('welcome');
})->name('home');
