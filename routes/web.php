<?php

use App\Http\Controllers;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\KKQController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ResidentController;
use App\Http\Controllers\Cetakkartu;
use App\Http\Controllers\statistics\Statistics;


use Illuminate\Support\Facades\Route;

//AUTH
Route::get('/', [AuthController::class, 'login'])->name('loginpage');
Route::post('/login', [AuthController::class, 'authenticate'])->name('loginpage');
Route::post('/logout', [AuthController::class, 'logout'])->name('loginpage');
Route::get('/register', [AuthController::class, 'registerView'])->name('registerpage');
Route::post('/register', [AuthController::class, 'register'])->name('registerpage');

//DASHBOARD
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard')->middleware('role:Admin');



//ANALYTICS
Route::get('/statistics', [Statistics::class, 'index'])->name('statistics')->middleware('role:Admin');
Route::get('/statistics/krama-adat' , [Statistics::class, 'kramaAdat'])->name('statistics.krama-adat')->middleware('role:Admin');

Route::middleware(['auth'])->group(function () {
    Route::get('/KKQ', [KKQController::class, 'index'])
        ->name('KKQ.index')
        ->middleware('role:Admin');

    Route::get('/NIKQ/{nik}/print', [KKQController::class, 'printCard'])
        ->name('KKQ.print')
        ->middleware('role:Admin');

    Route::get('/KKQ/{no_kk}/print', [KKQController::class, 'printCardFamily'])
        ->name('KKQFam.print')
        ->middleware('role:Admin');
});

// routes/web.php
Route::get('/pdf-preview/{filename}', function ($filename) {
    return response()->file(storage_path('app/' . $filename));
});



Route::get('/cetak-kartu' , [Cetakkartu::class, 'index'])->name('Cetakkartu')->middleware('role:Admin');

Route::get('/settings', function () {
    return view('pages.settings.index');
})->name('settings')->middleware('role:Admin');

Route::get('/input', function () {
    return view('pages.inputdata.index');
})->name('input')->middleware('role:Admin');

Route::get('/reports', function () {
    return view('pages.dashboard');
})->name('reports')->middleware('role:Admin');

Route::get('/database', [ResidentController::class, 'index'])->name('database')->middleware('role:Admin');

Route::get('/hosting', function () {
    return view('pages.dashboard');
})->name('hosting')->middleware('role:Admin');
