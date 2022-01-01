<?php

use App\Http\Controllers\Backend\{
    DashboardController,
    RoleController,
    UserController,
};
use Illuminate\Support\Facades\Route;

// Main Dashboard view
Route::get('/dashboard', DashboardController::class)->name('dashboard');

// Role Operation
Route::resource('/roles', RoleController::class)->except('show');
// User Route
Route::post('/users/{is}/restore', [UserController::class, 'restore'])->name('user.restore');
Route::delete('/users/{is}/forcedelete', [UserController::class, 'forceDelete'])->name('user.forcedelete');
Route::resource('/users', UserController::class);
