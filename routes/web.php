<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('pages.testcalendar');
})->name('landingpage');

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