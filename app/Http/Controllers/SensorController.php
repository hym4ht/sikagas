<?php

namespace App\Http\Controllers;

use App\Models\SensorLog;
use Illuminate\Http\Request;

class SensorController extends Controller
{
    /**
     * Store new sensor data.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'gas_level' => 'required|integer',
            'suhu' => 'required|numeric',
            'api_detected' => 'required|boolean',
            'apar_status' => 'required|string',
        ]);

        $log = SensorLog::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Data sensor berhasil disimpan.',
            'data' => $log
        ], 201);
    }

    /**
     * Get the latest sensor reading.
     */
    public function getLatest()
    {
        $latest = SensorLog::latest()->first();

        if (!$latest) {
            return response()->json([
                'gas_level' => 120,
                'suhu' => 27.0,
                'api_detected' => false,
                'apar_status' => 'SIAP',
                'created_at' => now()->toDateTimeString()
            ]);
        }

        return response()->json($latest);
    }
}
