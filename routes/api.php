<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// Route-route ini TIDAK memerlukan otentikasi (Publik)
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Route-route di dalam grup ini MEMERLUKAN otentikasi JWT
// Middleware 'auth:api' akan melindungi mereka.
Route::middleware('auth:api')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user-profile', [AuthController::class, 'userProfile']);
    // Anda bisa menambahkan route terproteksi lainnya di sini
});