<?php

namespace App\Http\Controllers;

use App\Models\SensorLog; // <-- PENTING: Import model SensorLog
use Illuminate\Http\Request;
use Illuminate\View\View;

class HistoryController extends Controller
{
    /**
     * Menampilkan halaman riwayat (history) data sensor
     * yang diambil dari database.
     *
     * @return \Illuminate\View\View
     */
    public function index(): View
    {
        // 1. Ambil data dari database menggunakan model SensorLog.
        // - latest() mengurutkan data dari yang paling baru (berdasarkan created_at).
        // - paginate(50) membatasi data yang diambil per halaman untuk performa, 
        //   dan akan otomatis membuat link navigasi halaman.
        $logs = SensorLog::latest()->paginate(50);

        // 2. Kirim data logs ke view 'dashboard.history'.
        //    Kita tidak perlu mengirim firebaseConfig ke halaman ini.
        return view('dashboard.history', [
            'logs' => $logs
        ]);
    }
}