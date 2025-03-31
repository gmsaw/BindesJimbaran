<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return 'lalala';
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard');

Route::get('/analytics', function () {
    return view('dashboard');
})->name('analytics');

Route::get('/settings', function () {
    return view('dashboard');
})->name('settings');

Route::get('/users', function () {
    return view('dashboard');
})->name('users');

Route::get('/reports', function () {
    return view('dashboard');
})->name('reports');

Route::get('/database', function () {
    return view('dashboard');
})->name('database');

Route::get('/hosting', function () {
    return view('dashboard');
})->name('hosting');