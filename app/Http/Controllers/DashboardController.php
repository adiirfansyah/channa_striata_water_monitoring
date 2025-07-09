<?php
// File: app/Http/Controllers/DashboardController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View; // Import class View

class DashboardController extends Controller
{
    /**
     * Menampilkan halaman monitoring real-time.
     *
     * @return \Illuminate\View\View
     */
    public function monitoring(): View
    {
        // 1. Ambil konfigurasi dari config/services.php
        $firebaseConfig = config('services.firebase');

        // 2. Kirim variabel $firebaseConfig ke view 'dashboard.monitoring'
        return view('dashboard.monitoring', [
            'firebaseConfig' => $firebaseConfig
        ]);
    }

    /**
     * Menampilkan halaman riwayat (history) data sensor.
     *
     * @return \Illuminate\View\View
     */
    public function history(): View
    {
        // 1. Ambil konfigurasi dari config/services.php
        $firebaseConfig = config('services.firebase');

        // 2. Kirim variabel $firebaseConfig ke view 'dashboard.history'
        //    Ini adalah perbaikan untuk error Anda.
        return view('dashboard.history', [
            'firebaseConfig' => $firebaseConfig
        ]);
    }
}