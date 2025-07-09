<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthController extends Controller
{
    // Menampilkan halaman/form login
    public function showLoginForm()
    {
        return view('auth.login');
    }

    // Memproses data dari form login
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        // Mencoba melakukan otentikasi menggunakan guard 'web' (default)
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate(); // Mencegah session fixation
            return redirect()->intended('dashboard'); // Redirect ke dashboard
        }

        // Jika gagal, kembali ke halaman login dengan pesan error
        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    // Menampilkan halaman/form registrasi
    public function showRegisterForm()
    {
        return view('auth.register');
    }

    // Memproses data dari form registrasi
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|confirmed|min:8',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // Setelah user dibuat, langsung loginkan user tersebut
        Auth::login($user);

        return redirect('/dashboard');
    }


    // Memproses logout
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }

    public function dashboard()
    {
        // Menampilkan halaman utama dashboard
        return view('dashboard.index'); // Kita akan buat file ini
    }

    // Tambahkan method ini
    public function monitoring()
    {
        // Ambil konfigurasi web dari file config/firebase.php
        // Pastikan Anda sudah mengisi bagian 'web' di config/firebase.php jika ada
        // atau definisikan langsung di sini dari info yang Anda dapat dari Firebase Console.
        $firebaseConfig = [
            'apiKey' => config('services.firebase.api_key'), // Ambil dari .env
            'authDomain' => config('services.firebase.auth_domain'),
            'databaseURL' => config('services.firebase.database_url'),
            'projectId' => config('services.firebase.project_id'),
            'storageBucket' => config('services.firebase.storage_bucket'),
            'messagingSenderId' => config('services.firebase.messaging_sender_id'),
            'appId' => config('services.firebase.app_id'),
        ];

        return view('dashboard.monitoring', ['firebaseConfig' => $firebaseConfig]);
    }

    // Tambahkan method ini
    public function history()
    {
        return view('dashboard.history'); // Kita akan buat file ini
    }
}
