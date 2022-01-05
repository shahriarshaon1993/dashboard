<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Frontend\PageController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return "done";
});

// Dinamic page route | This route allows define in the end
Route::get('{slug}', [PageController::class, 'index'])->name('page');