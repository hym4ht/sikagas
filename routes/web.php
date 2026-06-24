<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('dashboard');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard');

Route::get('/notifikasi', function () {
    return view('notifikasi');
})->name('notifikasi');

Route::get('/apar', function () {
    return view('apar');
})->name('apar');

Route::get('/contact', function () {
    return view('contact');
})->name('contact');