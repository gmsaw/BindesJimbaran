<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

//AUTH
Route::get('/', [AuthController::class, 'login'])->name('loginpage');
Route::post('/login', [AuthController::class, 'authenticate'])->name('loginpage');
Route::post('/logout', [AuthController::class, 'logout'])->name('loginpage');
Route::get('/register', [AuthController::class, 'registerView'])->name('registerpage');
Route::post('/register', [AuthController::class, 'register'])->name('registerpage');

//DASHBOARD
Route::get('/dashboard', function () {
    return view('pages.dashboard');
})->name('dashboard');

Route::get('/analytics', function () {
    return view('pages.dashboard');
})->name('analytics');

Route::get('/settings', function () {
    return view('pages.dashboard');
})->name('settings');

Route::get('/input', function () {
    return view('pages.inputdata');
})->name('input');

Route::get('/reports', function () {
    return view('pages.dashboard');
})->name('reports');

Route::get('/database', function () {
    return view('pages.dashboard');
})->name('database');

Route::get('/hosting', function () {
    return view('pages.dashboard');
})->name('hosting');