<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SensorController;
use App\Models\SensorLog;

Route::get('/', function () {
    $latestLog = SensorLog::latest()->first();
    return view('dashboard', compact('latestLog'));
});

Route::get('/dashboard', function () {
    $latestLog = SensorLog::latest()->first();
    return view('dashboard', compact('latestLog'));
})->name('dashboard');

Route::get('/notifikasi', function () {
    $logs = SensorLog::latest()->take(50)->get();
    return view('notifikasi', compact('logs'));
})->name('notifikasi');

Route::get('/apar', function () {
    return view('apar');
})->name('apar');

Route::get('/contact', function () {
    return view('contact');
})->name('contact');

// API Routes for IoT and AJAX
Route::post('/api/sensor', [SensorController::class, 'store']);
Route::get('/api/sensor/latest', [SensorController::class, 'getLatest']);