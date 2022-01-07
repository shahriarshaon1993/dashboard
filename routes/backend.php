<?php

use App\Http\Controllers\Backend\{
    BackupController,
    DashboardController,
    PageController,
    ProfileController,
    RoleController,
    SettingController,
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
Route::post('/pages/image/upload', [PageController::class, 'storeImage'])->name('pages.image.upload');
Route::resource('/pages', PageController::class);

// Settings
Route::as('settings.')->prefix('settings')->group(function () {
    // General
    Route::get('general', [SettingController::class, 'general'])->name('general');
    Route::put('general', [SettingController::class, 'generalUpdate'])->name('general.update');
    // Appearance
    Route::get('appearance', [SettingController::class, 'appearance'])->name('appearance');
    Route::put('appearance', [SettingController::class, 'appearanceUpdate'])->name('appearance.update');
    // Mail
    Route::get('mail', [SettingController::class, 'mail'])->name('mail');
    Route::put('mail', [SettingController::class, 'mailUpdate'])->name('mail.update');
});