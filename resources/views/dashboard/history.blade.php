@extends('layouts.dashboard')

@section('title', 'History')

@push('styles')
{{-- Menggunakan Google Fonts yang sudah ada --}}
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
{{-- Bootstrap Icons --}}
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">

<style>
    /* Variabel Warna untuk kemudahan kustomisasi */
    :root {
        --bs-primary-rgb: 78, 115, 223;
        --background-color: #f0f2f5;
        --card-bg: #ffffff;
        --text-dark: #343a40;
        --text-muted: #6c757d;
        --border-color: #e3e6f0;
    }

    body {
        font-family: 'Poppins', sans-serif;
        background-color: var(--background-color);
        color: var(--text-dark);
    }

    .h2 {
        font-weight: 600;
        color: var(--text-dark);
    }

    /* Desain Kartu yang Modern */
    .card {
        border-radius: 0.75rem;
        box-shadow: 0 0.5rem 1.5rem rgba(0, 0, 0, 0.07);
        border: none;
        background-color: var(--card-bg);
    }

    .card-header {
        background-color: transparent;
        border-bottom: 1px solid var(--border-color);
        padding: 1rem 1.5rem;
        font-weight: 600;
        color: rgb(var(--bs-primary-rgb));
    }

    /* Desain Tabel yang Bersih */
    .table {
        border-collapse: separate;
        border-spacing: 0 0.25rem;
    }

    .table th {
        font-weight: 500;
        color: var(--text-muted);
        background-color: transparent;
        border-top: none;
        border-bottom: 2px solid var(--border-color);
        padding: 1rem;
        text-transform: uppercase;
        font-size: 0.8rem;
        letter-spacing: 0.5px;
    }

    .table td {
        vertical-align: middle;
        color: var(--text-dark);
        background-color: var(--card-bg);
        padding: 1rem;
        border-top: 1px solid var(--border-color);
        border-bottom: 1px solid var(--border-color);
    }
    
    .table tr td:first-child { border-top-left-radius: 0.5rem; border-bottom-left-radius: 0.5rem; }
    .table tr td:last-child { border-top-right-radius: 0.5rem; border-bottom-right-radius: 0.5rem; }

    .table-hover tbody tr:hover td {
        background-color: rgba(var(--bs-primary-rgb), 0.05);
    }

    /* Indikator Visual (Badges) */
    .status-badge {
        display: inline-block;
        padding: 0.35em 0.65em;
        font-size: .75em;
        font-weight: 700;
        line-height: 1;
        color: #fff;
        text-align: center;
        white-space: nowrap;
        vertical-align: baseline;
        border-radius: 50rem;
    }

    .badge-normal { background-color: #1cc88a; }
    .badge-warning { background-color: #f6c23e; }
    .badge-danger { background-color: #e74a3b; }
    .badge-info { background-color: #36b9cc; }
    .badge-alkaline { background-color: #4e73df; }
    .badge-acidic { background-color: #fd7e14; }
</style>
@endpush

@section('dashboard-content')
    {{-- HEADER HALAMAN HISTORY YANG SUDAH DISIMPLIFIKASI --}}
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-4">
        <h1 class="h2">Riwayat Data Sensor</h1>
        <div class="btn-toolbar mb-2 mb-md-0">
            <button type="button" class="btn btn-sm btn-outline-secondary d-flex align-items-center">
                <i class="bi bi-calendar3 me-1"></i>
                {{ date('F j, Y') }}
            </button>
        </div>
    </div>

    {{-- KARTU TABEL RIWAYAT --}}
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Log Data Tersimpan</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col"><i class="bi bi-clock me-2"></i>Waktu</th>
                            <th scope="col"><i class="bi bi-thermometer-half me-2"></i>Suhu</th>
                            <th scope="col"><i class="bi bi-droplet-half me-2"></i>pH Air</th>
                            <th scope="col"><i class="bi bi-eye me-2"></i>Kekeruhan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($logs as $log)
                            <tr>
                                <td><strong>{{ $loop->iteration + ($logs->currentPage() - 1) * $logs->perPage() }}</strong></td>
                                <td>{{ $log->created_at->format('d M Y, H:i') }}<br><small class="text-muted">{{ $log->created_at->diffForHumans() }}</small></td>
                                <td>
                                    {{ number_format($log->suhu, 1) }} °C
                                    @if ($log->suhu > 30)
                                        <span class="status-badge badge-danger">Tinggi</span>
                                    @elseif ($log->suhu < 25)
                                        <span class="status-badge badge-info">Rendah</span>
                                    @else
                                        <span class="status-badge badge-normal">Normal</span>
                                    @endif
                                </td>
                                <td>
                                    {{ number_format($log->ph, 1) }}
                                    @if ($log->ph > 7.5)
                                        <span class="status-badge badge-alkaline">Basa</span>
                                    @elseif ($log->ph < 6.5)
                                        <span class="status-badge badge-acidic">Asam</span>
                                    @else
                                        <span class="status-badge badge-normal">Netral</span>
                                    @endif
                                </td>
                                <td>
                                    {{ number_format($log->kekeruhan, 0) }} NTU
                                    @if ($log->kekeruhan > 50)
                                        <span class="status-badge badge-danger">Keruh</span>
                                    @elseif ($log->kekeruhan > 10)
                                        <span class="status-badge badge-warning">Sedang</span>
                                    @else
                                        <span class="status-badge badge-normal">Jernih</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-5">
                                    <i class="bi bi-cloud-drizzle fs-1 text-muted"></i>
                                    <h5 class="mt-3">Belum Ada Data Riwayat</h5>
                                    <p class="text-muted">Data sensor yang tersimpan akan muncul di sini.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- NAVIGASI HALAMAN SIMPLE --}}
            {{-- Wrapper ini akan memastikan tombol-tombol berada di kanan --}}
            @if ($logs->hasPages())
                <div class="mt-4 d-flex justify-content-end align-items-center">
                    {{-- Tombol Halaman Sebelumnya --}}
                    {{-- Muncul jika bukan halaman pertama --}}
                    @if (!$logs->onFirstPage())
                        <a href="{{ $logs->previousPageUrl() }}" class="btn btn-outline-primary">
                            <i class="bi bi-arrow-left me-1"></i> Sebelumnya
                        </a>
                    @endif

                    {{-- Tombol Halaman Selanjutnya --}}
                    {{-- Muncul jika ada halaman berikutnya --}}
                    @if ($logs->hasMorePages())
                        <a href="{{ $logs->nextPageUrl() }}" class="btn btn-primary ms-2">
                            Selanjutnya <i class="bi bi-arrow-right ms-1"></i>
                        </a>
                    @endif
                </div>
            @endif

        </div>
    </div>
@endsection