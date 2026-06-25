<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SensorController;

use App\Models\SensorData;

Route::get('/', function () {
    $latestData = SensorData::latest()->first();
    return view('dashboard', compact('latestData'));
});

Route::get('/dashboard', function () {
    $latestData = SensorData::latest()->first();
    return view('dashboard', compact('latestData'));
})->name('dashboard');

Route::get('/notifikasi', function () {
    $logs = SensorData::latest()->take(50)->get();
    return view('notifikasi', compact('logs'));
})->name('notifikasi');

use Illuminate\Support\Facades\Cache;

Route::get('/apar', function () {
    $aparControl = Cache::get('apar_control', 'on');
    return view('apar', compact('aparControl'));
})->name('apar');

Route::get('/contact', function () {
    return view('contact');
})->name('contact');

// API untuk IoT ESP32
Route::post('/api/sensor', [SensorController::class, 'store']);
Route::get('/api/sensor/latest', [SensorController::class, 'latest']);
Route::get('/api/sensor/history', [SensorController::class, 'history']);
Route::post('/api/apar/toggle', [SensorController::class, 'toggleApar']);