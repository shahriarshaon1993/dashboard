<?php

use App\Http\Controllers\Backend\{
    DashboardController,
    RoleController,
};
use Illuminate\Support\Facades\Route;

// Main Dashboard view
Route::get('/dashboard', DashboardController::class)->name('dashboard');

// Role Operation
Route::resource('/roles', RoleController::class)->except('show');