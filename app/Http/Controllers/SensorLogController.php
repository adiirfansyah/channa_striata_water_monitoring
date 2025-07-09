<?php

namespace App\Http\Controllers;

use App\Models\SensorLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log; // <-- DITAMBAHKAN: Untuk mencatat log error
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
        //    Memastikan semua field yang dibutuhkan ada dan berupa angka.
        $validator = Validator::make($request->all(), [
            'suhu' => 'required|numeric',
            'ph' => 'required|numeric',
            'kekeruhan' => 'required|numeric',
        ]);

        // Jika validasi gagal, kirim respons error 422 dengan detail kesalahan.
        if ($validator->fails()) {
            return response()->json([
                'status' => 'error', 
                'message' => 'Data yang dikirim tidak valid.',
                'errors' => $validator->errors()
            ], 422);
        }

        // 2. Coba simpan data ke database.
        try {
            // Gunakan metode create() untuk membuat record baru di tabel sensor_logs.
            SensorLog::create([
                'suhu' => $request->suhu,
                'ph' => $request->ph,
                'kekeruhan' => $request->kekeruhan,
            ]);

            // Jika berhasil, kirim respons sukses.
            return response()->json([
                'status' => 'success', 
                'message' => 'Data logged successfully.'
            ]);

        } catch (\Exception $e) {
            // 3. Jika terjadi error saat menyimpan (misal: koneksi DB putus, nama kolom salah).
            
            // PENYESUAIAN KUNCI: Catat pesan error yang detail ke file log Laravel.
            // Ini sangat penting untuk debugging di sisi server.
            Log::error('Gagal menyimpan log sensor: ' . $e->getMessage());

            // Kirim respons error 500 (Internal Server Error) ke frontend.
            // Pesan yang dikirim ke pengguna tetap umum untuk keamanan.
            return response()->json([
                'status' => 'error', 
                'message' => 'Terjadi kesalahan pada server saat menyimpan data.'
            ], 500);
        }
    }
}