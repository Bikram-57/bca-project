<?php

use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ExcelController;
use App\Http\Controllers\ProfileController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('auth.login');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::post('/upload', [ExcelController::class, 'fileUpload'])->name('file-upload');

    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::get('upload', function () {
        return view('upload');
    })->name('upload');

    Route::get('fetch', [DashboardController::class, 'fetchView'])->name('fetch');
    Route::post('fetch', [DashboardController::class, 'fetchData'])->name('fetch-data');

    Route::get('semester', function () {
        return view('semester');
    })->name('semester');

    Route::get('year', function () {
        return view('year');
    })->name('year');

    Route::get('validation', function () {
        return view('validation');
    })->name('validation');
});

require __DIR__.'/auth.php';
