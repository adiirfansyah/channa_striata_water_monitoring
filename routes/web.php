<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HistoryController;
use App\Http\Controllers\SensorLogController; // Pastikan ini ditambahkan

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
    
    // Route untuk halaman history, menggunakan HistoryController yang benar.
    Route::get('/dashboard/history', [HistoryController::class, 'index'])->name('dashboard.history');

    
    // --- Route untuk Aksi (Actions) ---
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    
    // Route untuk menerima data dari JavaScript di halaman monitoring.
    // Ini PENTING untuk menyimpan data ke database.
    Route::post('/log-sensor-data', [SensorLogController::class, 'store'])->name('sensor.log.store');

});