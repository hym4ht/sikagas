<?php

namespace App\Http\Controllers;

use App\Models\SensorData;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
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

        // Ambil data terbaru sebelum data baru dibuat untuk mengecek perubahan status
        $latest = SensorData::latest()->first();

        $sensor = SensorData::create([
            'gas_value'    => $request->gas_value,
            'gas_ppm'      => $request->gas_ppm ?? null,
            'status'       => $request->status,
            'apar_aktif'   => $request->apar_aktif ?? false,
            'buzzer_aktif' => $request->buzzer_aktif ?? false,
        ]);

        // Kirim WhatsApp jika status BAHAYA dan sebelumnya bukan BAHAYA (menghindari spamming)
        if ($request->status === 'BAHAYA' && (!$latest || $latest->status !== 'BAHAYA')) {
            $this->kirimWhatsApp('BAHAYA', $request->gas_value);
        }

        // Kirim WhatsApp jika status WASPADA dan sebelumnya AMAN (transisi pertama)
        if ($request->status === 'WASPADA' && (!$latest || $latest->status === 'AMAN')) {
            $this->kirimWhatsApp('WASPADA', $request->gas_value);
        }

        return response()->json([
            'success'      => true,
            'apar_control' => Cache::get('apar_control', 'on'),
        ], 200);
    }

    /**
     * Kirim notifikasi WhatsApp menggunakan Fonnte API Gateway
     *
     * @param string $status  'BAHAYA' | 'WASPADA'
     * @param int    $gasValue Nilai ADC dari sensor MQ2
     */
    private function kirimWhatsApp(string $status, int $gasValue)
    {
        $token  = env('FONNTE_TOKEN');
        $target = env('WHATSAPP_TARGET');

        if (empty($token) || $token === 'your_fonnte_token_here' || empty($target)) {
            Log::warning('WhatsApp notification skipped: Token or target is not configured.');
            return;
        }

        if ($status === 'BAHAYA') {
            $pesan = "🚨 *BAHAYA! DETEKSI KEBOCORAN GAS* 🚨\n\n"
                   . "Kadar Gas : *{$gasValue}*\n"
                   . "Status    : *BAHAYA*\n"
                   . "Tindakan  : *APAR OTOMATIS SUDAH DIAKTIFKAN!*\n\n"
                   . "⚠️ Harap segera periksa lokasi tabung gas Anda!";
        } else {
            $pesan = "⚠️ *PERINGATAN! KADAR GAS MENINGKAT* ⚠️\n\n"
                   . "Kadar Gas : *{$gasValue}*\n"
                   . "Status    : *WASPADA*\n\n"
                   . "Harap segera periksa kondisi ruangan dan ventilasi.";
        }

        try {
            $response = Http::withHeaders([
                'Authorization' => $token,
            ])->asForm()->post('https://api.fonnte.com/send', [
                'target'  => $target,
                'message' => $pesan,
            ]);

            if ($response->failed()) {
                Log::error('Fonnte API Error: ' . $response->body());
            } else {
                Log::info("WhatsApp [{$status}] notification sent. Gas: {$gasValue}");
            }
        } catch (\Exception $e) {
            Log::error('WhatsApp notification failed: ' . $e->getMessage());
        }
    }

    /**
     * Ambil data sensor terbaru (untuk ditampilkan di dashboard)
     * Endpoint: GET /api/sensor/latest
     */
    public function latest()
    {
        $data = SensorData::latest()->first();

        $response = $data ? $data->toArray() : [
            'gas_value'    => 120,
            'status'       => 'AMAN',
            'apar_aktif'   => false,
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
     * Mengatur status APAR dari Web (Cache-based)
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
            'status'  => $request->status,
        ]);
    }
}
