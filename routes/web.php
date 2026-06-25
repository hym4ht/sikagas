<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SensorController;

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

// API untuk IoT ESP32
Route::post('/api/sensor', [SensorController::class, 'store']);
Route::get('/api/sensor/latest', [SensorController::class, 'latest']);
Route::get('/api/sensor/history', [SensorController::class, 'history']);