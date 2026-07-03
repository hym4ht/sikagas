<?php

namespace App\Http\Controllers;

use App\Models\AparCommand;
use App\Models\SensorData;
use Illuminate\Http\Request;

class AparController extends Controller
{
    /**
     * Terima perintah ON/OFF dari halaman web
     * Endpoint: POST /api/apar/control
     */
    public function control(Request $request)
    {
        $request->validate([
            'command' => 'required|in:ON,OFF',
        ]);

        AparCommand::create([
            'command' => $request->command,
            'source'  => 'web',
        ]);

        return response()->json([
            'success' => true,
            'command' => $request->command,
        ]);
    }

    /**
     * ESP32 polling: ambil perintah terbaru
     * Endpoint: GET /api/apar/command
     */
    public function latestCommand()
    {
        $cmd = AparCommand::latest()->first();

        return response()->json([
            'command' => $cmd ? $cmd->command : 'OFF',
        ]);
    }

    /**
     * Status gabungan: perintah manual + kondisi sensor aktual
     * Endpoint: GET /api/apar/status
     */
    public function status()
    {
        $cmd    = AparCommand::latest()->first();
        $sensor = SensorData::latest()->first();

        return response()->json([
            'command'        => $cmd ? $cmd->command : 'OFF',
            'sensor_status'  => $sensor ? $sensor->status : 'AMAN',
            'apar_aktif'     => $sensor ? (bool) $sensor->apar_aktif : false,
            'gas_value'      => $sensor ? $sensor->gas_value : 0,
            'last_updated'   => $sensor ? $sensor->updated_at : null,
        ]);
    }
}
