{{-- File: resources/views/dashboard/index.blade.php --}}

@extends('layouts.dashboard')

@section('title', 'Dashboard - Monitoring Ikan Gabus')

{{-- Menambahkan CSS kustom dan Font langsung ke halaman ini --}}
@push('styles')
    <!-- 1. Google Fonts: Poppins -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        /* ==================================
           TEMA VISUAL "AQUA-TECH" DASHBOARD
           ================================== */
        
        /* 1. Pengaturan Dasar & Tipografi */
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f4f7f6; /* Warna latar belakang yang sangat lembut, nuansa hijau/biru */
        }

        h1, h2, h3, h4, h5, h6 {
            font-weight: 600; /* Membuat judul lebih tegas */
        }
        
        /* 2. Banner Selamat Datang dengan Gradient */
        .welcome-banner-gradient {
            background: linear-gradient(135deg, #0d6efd 0%, #198754 100%);
            color: white;
            border-radius: 1rem; /* Sudut lebih bulat */
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        }
        .welcome-banner-gradient h1, .welcome-banner-gradient p {
            color: white; /* Pastikan teks di dalam banner berwarna putih */
        }

        /* 3. Kartu Fitur (Feature Card) yang Didesain Ulang */
        .feature-card {
            background-color: #ffffff;
            border: none;
            border-radius: 1rem; /* Sudut lebih bulat */
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.05); /* Bayangan yang halus */
            transition: all 0.3s ease-in-out;
            height: 100%; /* Memastikan semua kartu punya tinggi yang sama */
            display: flex;
            flex-direction: column;
        }

        .feature-card:hover {
            transform: translateY(-8px); /* Efek 'mengangkat' saat di-hover */
            box-shadow: 0 12px 28px rgba(0, 0, 0, 0.08);
        }
        
        .feature-card .card-body {
            display: flex;
            flex-direction: column;
            justify-content: center; /* Konten di tengah secara vertikal */
            flex-grow: 1;
        }
        
        /* 4. Lingkaran Ikon yang Menonjol */
        .card-icon-wrapper {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem auto; /* Otomatis di tengah dan beri jarak bawah */
            font-size: 2.5rem; /* Ukuran ikon di dalamnya */
        }
        
        .icon-primary {
            background-color: rgba(13, 110, 253, 0.1); /* Latar belakang transparan warna biru */
            color: #0d6efd; /* Warna ikon biru solid */
        }

        .icon-success {
            background-color: rgba(25, 135, 84, 0.1); /* Latar belakang transparan warna hijau */
            color: #198754; /* Warna ikon hijau solid */
        }

    </style>
@endpush

@section('dashboard-content')

    {{-- 1. Banner Selamat Datang dengan Gaya Baru --}}
    <div class="p-5 mb-5 welcome-banner-gradient">
        <div class="container-fluid py-3">
            <h1 class="display-5 fw-bold">Sistem Monitoring Ikan Gabus</h1>
            <p class="col-md-10 fs-4">
                Selamat datang, <strong>{{ Auth::user()->name }}</strong>! Pantau kondisi kolam Anda dengan mudah dan efisien.
            </p>
        </div>
    </div>

    {{-- 2. Judul Bagian Fitur --}}
    <h2 class="pb-2 border-bottom mb-4">Fitur Utama</h2>

    {{-- 3. Kartu Fitur dengan Desain Baru --}}
    <div class="row g-4"> {{-- 'g-4' menambah jarak antar kartu --}}
        
        {{-- Kartu Monitoring Real-Time --}}
        <div class="col-lg-6 col-md-12 mb-4">
            <div class="feature-card">
                <div class="card-body text-center p-4">
                    <div class="card-icon-wrapper icon-primary">
                        <i class="fas fa-desktop"></i>
                    </div>
                    <h5 class="card-title fs-4">Monitoring Real-Time</h5>
                    <p class="card-text text-muted">
                        Lihat parameter vital seperti suhu, pH, dan kekeruhan air secara langsung dari sensor yang terpasang di kolam Anda.
                    </p>
                </div>
            </div>
        </div>

        {{-- Kartu Analisis & Riwayat Data --}}
        <div class="col-lg-6 col-md-12 mb-4">
            <div class="feature-card">
                <div class="card-body text-center p-4">
                     <div class="card-icon-wrapper icon-success">
                        <i class="fas fa-chart-line"></i>
                    </div>
                    <h5 class="card-title fs-4">Analisis & Riwayat Data</h5>
                    <p class="card-text text-muted">
                        Akses riwayat data untuk melihat tren dan menganalisis kondisi kolam dari waktu ke waktu.
                    </p>
                </div>
            </div>
        </div>
        
    </div>

@endsection