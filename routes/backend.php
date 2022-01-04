<?php

use App\Http\Controllers\Backend\{
    BackupController,
    DashboardController,
    PageController,
    ProfileController,
    RoleController,
    UserController,
};
use Illuminate\Support\Facades\Route;

// Main Dashboard view
Route::get('/dashboard', DashboardController::class)->name('dashboard');

// Role Operation
Route::resource('/roles', RoleController::class)->except('show');
// User Route
Route::post('/users/{id}/restore', [UserController::class, 'restore'])->name('user.restore');
Route::delete('/users/{id}/forcedelete', [UserController::class, 'forceDelete'])->name('user.forcedelete');
Route::resource('/users', UserController::class);

// Profile
Route::get('profile', [ProfileController::class, 'index'])->name('profile.index');
Route::put('profile', [ProfileController::class, 'update'])->name('profile.update');

// Security
Route::get('profile/security', [ProfileController::class, 'changePassword'])->name('profile.password.change');
Route::put('profile/security', [ProfileController::class, 'updatePassword'])->name('profile.password.update');

// Backups
Route::resource('backups', BackupController::class)->only(['index', 'store', 'destroy']);
Route::get('backups/{file_name}', [BackupController::class, 'download'])->name('backups.download');
Route::delete('backups', [BackupController::class, 'clean'])->name('backups.clean');

// Pages
Route::resource('/pages', PageController::class);