<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Cache;
use App\Models\SensorData;
use App\Http\Controllers\SensorController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AparController;

Route::get('/', function () {
    return redirect()->route('dashboard');
});

// Autentikasi
Route::get('/login', [AuthController::class, 'showLogin'])->name('login')->middleware('guest');
Route::post('/login', [AuthController::class, 'login'])->middleware('guest');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// Halaman Terproteksi Auth
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', function () {
        $latestData = SensorData::latest()->first();
        return view('dashboard', compact('latestData'));
    })->name('dashboard');

    Route::get('/notifikasi', function (\Illuminate\Http\Request $request) {
        $query = SensorData::latest();

        if ($request->tanggal) {
            $query->whereDate('created_at', $request->tanggal);
        }
        if ($request->bulan) {
            $query->whereMonth('created_at', $request->bulan);
        }
        if ($request->tahun) {
            $query->whereYear('created_at', $request->tahun);
        }

        $logs = $query->paginate(50)->withQueryString();
        return view('notifikasi', compact('logs'));
    })->name('notifikasi');

    Route::get('/apar', function () {
        $aparControl = Cache::get('apar_control', 'on');
        return view('apar', compact('aparControl'));
    })->name('apar');

    Route::get('/contact', function () {
        return view('contact');
    })->name('contact');
});

// API untuk IoT ESP32 (tidak diproteksi Auth)
Route::post('/api/sensor', [SensorController::class, 'store']);
Route::get('/api/sensor/latest', [SensorController::class, 'latest']);
Route::get('/api/sensor/history', [SensorController::class, 'history']);

// API Kontrol APAR
Route::post('/api/apar/toggle', [SensorController::class, 'toggleApar']);    // Cache-based (lama)
Route::post('/api/apar/control', [AparController::class, 'control']);         // DB-based (baru)
Route::get('/api/apar/command', [AparController::class, 'latestCommand']);    // ESP32 polling
Route::get('/api/apar/status', [AparController::class, 'status']);            // Status gabungan
