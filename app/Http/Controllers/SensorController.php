<?php

namespace App\Http\Controllers;

use App\Models\SensorData;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Cache;

class SensorController extends Controller
{
    /**
     * Terima data dari IoT (ESP32) via POST
     * Endpoint: POST /api/sensor
     */
    public function store(Request $request)
    {
        // Validasi sederhana
        $request->validate([
            'gas_value' => 'required|integer',
            'status'    => 'required|string|in:AMAN,WASPADA,BAHAYA',
        ]);

        SensorData::create([
            'gas_value'    => $request->gas_value,
            'gas_ppm'      => $request->gas_ppm ?? null,
            'status'       => $request->status,
            'apar_aktif'   => $request->apar_aktif ?? false,
            'buzzer_aktif' => $request->buzzer_aktif ?? false,
        ]);

        return response()->json([
            'success' => true,
            'apar_control' => Cache::get('apar_control', 'on')
        ], 200);
    }

    /**
     * Ambil data sensor terbaru (untuk ditampilkan di dashboard)
     * Endpoint: GET /api/sensor/latest
     */
    public function latest()
    {
        $data = SensorData::latest()->first();
        
        $response = $data ? $data->toArray() : [
            'gas_value' => 120,
            'status' => 'AMAN',
            'apar_aktif' => false,
            'buzzer_aktif' => false,
        ];
        
        $response['apar_control'] = Cache::get('apar_control', 'on');
        
        return response()->json($response);
    }

    /**
     * Ambil histori sensor (untuk halaman notifikasi)
     * Endpoint: GET /api/sensor/history
     */
    public function history(Request $request)
    {
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

        return response()->json($query->paginate(50));
    }

    /**
     * Mengatur status APAR dari Web
     * Endpoint: POST /api/apar/toggle
     */
    public function toggleApar(Request $request)
    {
        $request->validate([
            'status' => 'required|string|in:on,off',
        ]);

        Cache::put('apar_control', $request->status);

        return response()->json([
            'success' => true,
            'status' => $request->status
        ]);
    }
}
