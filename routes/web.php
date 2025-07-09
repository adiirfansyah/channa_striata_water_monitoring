<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HistoryController;
use App\Http\Controllers\SensorLogController; // Pastikan ini ada

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Halaman utama, kita arahkan saja ke login
Route::get('/', function () {
    return redirect()->route('login');
});

// Route untuk tamu (yang belum login)
Route::middleware('guest')->group(function () {
    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);

    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

// Route yang hanya bisa diakses setelah login
Route::middleware('auth')->group(function () {
    
    // --- Route untuk Halaman (Pages) ---
    Route::get('/dashboard', [AuthController::class, 'dashboard'])->name('dashboard');
    Route::get('/dashboard/monitoring', [AuthController::class, 'monitoring'])->name('dashboard.monitoring');
    Route::get('/dashboard/history', [HistoryController::class, 'index'])->name('dashboard.history');

    
    // --- Route untuk Aksi (Actions) ---
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    
    // Route untuk MENYIMPAN data dari JavaScript ke database.
    // Anda menggunakan nama `log-sensor-data`, pastikan di file blade juga sama.
    Route::post('/sensor/log', [SensorLogController::class, 'store'])->name('sensor.log.store');

    // =========================================================================
    // ===                 TAMBAHKAN RUTE BARU DI BAWAH INI                    ===
    // =========================================================================
    
    // Route untuk MENGAMBIL data awal (10 terakhir) untuk mengisi grafik.
    // Ini akan dipanggil oleh fetch() di file monitoring.blade.php.
    Route::get('/monitoring/initial-data', [SensorLogController::class, 'getInitialData'])->name('monitoring.initial_data');

});