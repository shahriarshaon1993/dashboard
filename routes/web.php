<?php

declare(strict_types=1);

use App\Http\Controllers\ActivityLogController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', fn () => Inertia::render('Welcome'))->name('home');

Route::middleware(['auth', 'active', 'verified'])->group(function (): void {
    Route::get('dashboard', DashboardController::class)
        ->name('dashboard');

    Route::post('users/export', [UserController::class, 'export'])
        ->name('users.export');
    Route::post('users/import', [UserController::class, 'import'])
        ->name('users.import');
    Route::delete('users/bulk-destroy', [UserController::class, 'bulkDestroy'])
        ->name('users.bulk-destroy');
    Route::resource('users', UserController::class);

    Route::delete('activities/clear-history', [ActivityLogController::class, 'clearHistory'])
        ->name('activities.clear-history');
    Route::delete('activities/bulk-destroy', [ActivityLogController::class, 'bulkDestroy'])
        ->name('activities.bulk-destroy');
    Route::resource('activities', ActivityLogController::class);
});

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
