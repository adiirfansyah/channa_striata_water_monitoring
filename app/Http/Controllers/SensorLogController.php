<?php

namespace App\Http\Controllers;

use App\Models\SensorLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class SensorLogController extends Controller
{
    /**
     * Menerima dan menyimpan data sensor baru dari request.
     * Method ini dipanggil oleh JavaScript di halaman monitoring.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        // 1. Validasi data yang masuk dari request.
        $validator = Validator::make($request->all(), [
            'suhu' => 'required|numeric',
            'ph' => 'required|numeric',
            'kekeruhan' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error', 
                'message' => 'Data yang dikirim tidak valid.',
                'errors' => $validator->errors()
            ], 422);
        }

        // 2. Coba simpan data ke database.
        try {
            SensorLog::create([
                'suhu' => $request->suhu,
                'ph' => $request->ph,
                'kekeruhan' => $request->kekeruhan,
            ]);

            return response()->json([
                'status' => 'success', 
                'message' => 'Data logged successfully.'
            ]);

        } catch (\Exception $e) {
            Log::error('Gagal menyimpan log sensor: ' . $e->getMessage());

            return response()->json([
                'status' => 'error', 
                'message' => 'Terjadi kesalahan pada server saat menyimpan data.'
            ], 500);
        }
    }

    // ========================================================================
    // ===                 TAMBAHKAN METODE BARU DI BAWAH INI                 ===
    // ========================================================================

    /**
     * Mengambil 10 data sensor terakhir untuk inisialisasi grafik di frontend.
     * Method ini dipanggil oleh JavaScript saat halaman monitoring pertama kali dimuat.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getInitialData()
    {
        try {
            // 1. Ambil 10 data terakhir, diurutkan dari yang terbaru (latest).
            $logs = SensorLog::latest()->take(10)->get();

            // 2. Balik urutan collection agar menjadi dari terlama ke terbaru.
            //    Ini penting agar grafik menampilkan data secara kronologis (dari kiri ke kanan).
            $sortedLogs = $logs->reverse();

            // 3. Format data agar mudah digunakan oleh Chart.js di frontend.
            $formattedData = $sortedLogs->map(function ($log) {
                return [
                    'suhu' => (float) $log->suhu,
                    'ph' => (float) $log->ph,
                    'kekeruhan' => (float) $log->kekeruhan,
                    // Format waktu agar lebih ringkas untuk label grafik
                    'label' => $log->created_at->format('H:i:s') 
                ];
            });

            // 4. Kembalikan sebagai response JSON.
            //    ->values() digunakan untuk mereset key array setelah di-reverse menjadi [0, 1, 2, ...].
            return response()->json($formattedData->values());

        } catch (\Exception $e) {
            // Jika terjadi error saat mengambil data (misal: tabel tidak ada).
            Log::error('Gagal mengambil data awal sensor: ' . $e->getMessage());

            return response()->json([
                'status' => 'error',
                'message' => 'Gagal mengambil data riwayat dari server.'
            ], 500);
        }
    }
}