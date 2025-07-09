@extends('layouts.dashboard')

@section('title', 'History')

@push('styles')
{{-- Style ini sudah bagus dan konsisten, jadi kita biarkan --}}
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<style>
    body { font-family: 'Poppins', sans-serif; background-color: #f8f9fa; }
    .h2 { font-weight: 600; color: #343a40; }
    .card { border-radius: 1rem; box-shadow: 0 8px 24px rgba(0, 0, 0, 0.05); border: 1px solid #e9ecef; }
    .table th { font-weight: 600; color: #495057; background-color: #f8f9fa; border-top: none; }
    .table td { vertical-align: middle; color: #212529; }
    .table-hover tbody tr:hover { background-color: rgba(0, 123, 255, 0.05); }
    .pagination { justify-content: center; } /* Style untuk pagination */
</style>
@endpush

@section('dashboard-content')
    {{-- HEADER HALAMAN HISTORY --}}
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Riwayat Data Sensor</h1>
        <div class="btn-toolbar mb-2 mb-md-0">
            <div class="btn-group">
                <button type="button" class="btn btn-sm btn-outline-secondary">
                    <i class="bi bi-calendar3 me-1"></i>
                    {{ date('F j, Y') }}
                </button>
            </div>
        </div>
    </div>

    {{-- KARTU TABEL RIWAYAT --}}
    <div class="card">
        <div class="card-header bg-white border-bottom-0 pt-3">
            <h5 class="mb-0">Log Data Tersimpan</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Waktu</th>
                            <th scope="col">Suhu (°C)</th>
                            <th scope="col">pH Air</th>
                            <th scope="col">Kekeruhan (NTU)</th>
                        </tr>
                    </thead>
                    <tbody>
                        {{-- Loop data dari controller menggunakan Blade --}}
                        @forelse ($logs as $log)
                            <tr>
                                {{-- Nomor urut yang benar untuk pagination --}}
                                <td>{{ $loop->iteration + ($logs->currentPage() - 1) * $logs->perPage() }}</td>
                                {{-- Format waktu menggunakan Carbon (bawaan Laravel) --}}
                                <td>{{ $log->created_at->format('d M Y, H:i:s') }}</td>
                                {{-- Format angka untuk konsistensi --}}
                                <td>{{ number_format($log->suhu, 1) }}</td>
                                <td>{{ number_format($log->ph, 1) }}</td>
                                <td>{{ number_format($log->kekeruhan, 1) }}</td>
                            </tr>
                        @empty
                            {{-- Pesan ini akan tampil jika tidak ada data di database --}}
                            <tr>
                                <td colspan="5" class="text-center py-5">
                                    <i class="bi bi-x-circle fs-3 text-muted"></i>
                                    <p class="mt-2 mb-0">Tidak ada data riwayat yang ditemukan.</p>
                                    <small class="text-muted">Data akan muncul di sini setelah halaman monitoring menyimpan log pertama.</small>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Tampilkan link pagination di bawah tabel --}}
            <div class="mt-3 d-flex justify-content-center">
                {{ $logs->links() }}
            </div>
        </div>
    </div>
@endsection

{{-- TIDAK ADA @push('scripts') LAGI, KARENA TIDAK DIPERLUKAN --}}