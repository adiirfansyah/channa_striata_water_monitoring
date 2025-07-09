@extends('layouts.dashboard')

@section('title', 'Real-time Monitoring')

@push('styles')
{{-- Import Font dari Google Fonts --}}
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

<style>
    /* =================================
       1. PENGATURAN DASAR & TIPOGRAFI
       ================================= */
    body {
        font-family: 'Poppins', sans-serif;
        background-color: #f8f9fa;
    }

    .h2 {
        font-weight: 600;
        color: #343a40;
    }

    /* =================================
       2. DESAIN CARD BARU YANG MODERN
       ================================= */
    .stat-card {
        background-color: white;
        border-radius: 1rem;
        padding: 1.5rem;
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.05);
        border: 1px solid #e9ecef;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        display: flex;
        flex-direction: column;
        height: 100%;
    }

    .stat-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 12px 28px rgba(0, 0, 0, 0.08);
    }

    .stat-header {
        display: flex;
        align-items: center;
        margin-bottom: 1rem; /* Sedikit dikurangi */
    }

    .stat-icon {
        font-size: 1.75rem;
        min-width: 50px;
        height: 50px;
        border-radius: 50%;
        color: white;
        margin-right: 1rem;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: background-color 0.5s ease;
    }

    /* Warna ikon untuk Sensor */
    .icon-suhu { background-color: #fd7e14; }
    .icon-ph { background-color: #0d6efd; }
    .icon-kekeruhan { background-color: #6f42c1; }
    
    /* Warna dinamis untuk Kualitas Air */
    .icon-kualitas-baik { background-color: #198754; } /* Hijau */
    .icon-kualitas-cukup { background-color: #ffc107; } /* Kuning */
    .icon-kualitas-buruk { background-color: #dc3545; } /* Merah */

    /* Warna ikon untuk Aktuator */
    .icon-pompa-isi { background-color: #198754; }
    .icon-pompa-buang { background-color: #dc3545; }

    .stat-title {
        font-size: 1.1rem;
        font-weight: 500;
        color: #495057;
    }

    .stat-body {
        text-align: center;
        flex-grow: 1;
        display: flex;
        flex-direction: column;
        justify-content: center;
    }

    .stat-value {
        font-size: 2.75rem;
        font-weight: 700;
        color: #212529;
        line-height: 1.2;
    }
    
    .stat-deskripsi {
        font-size: 1rem;
        color: #6c757d;
        margin-top: 0.5rem;
    }

    /* =================================
       3. STYLE BARU UNTUK GRAFIK
       ================================= */
    .chart-wrapper {
        position: relative;
        height: 150px; /* Atur tinggi grafik */
        margin-top: 1rem;
    }

    .current-value-display {
        font-size: 1.75rem;
        font-weight: 600;
        color: #343a40;
        text-align: right;
        margin-bottom: -1.25rem; /* Posisikan di atas grafik */
        padding-right: 0.5rem;
    }

    .current-value-display .unit {
        font-size: 1rem;
        font-weight: 400;
        color: #6c757d;
        margin-left: 0.1rem;
    }

    /* =================================
       4. ANIMASI & EFEK VISUAL
       ================================= */
    .col-lg-4, .col-lg-12, .col-lg-6 {
        animation: fadeIn 0.5s ease-out forwards;
        opacity: 0;
    }
    
    .col-lg-4:nth-child(1) { animation-delay: 0.1s; }
    .col-lg-4:nth-child(2) { animation-delay: 0.2s; }
    .col-lg-4:nth-child(3) { animation-delay: 0.3s; }
    #kualitas-row .col-lg-12 { animation-delay: 0.4s; }
    #aktuator-row .col-lg-6:nth-child(1) { animation-delay: 0.5s; }
    #aktuator-row .col-lg-6:nth-child(2) { animation-delay: 0.6s; }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .flash-update {
        animation: flash 0.7s ease;
    }

    @keyframes flash {
        0%, 100% { background-color: white; }
        50% { background-color: rgba(0, 123, 255, 0.1); }
    }
</style>
@endpush

@section('dashboard-content')
    {{-- HEADER --}}
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Real-time Monitoring</h1>
        <div class="d-flex align-items-center">
            <span class="badge me-3" id="status-badge" style="transition: background-color 0.5s ease;">Connecting...</span>
            <div class="btn-toolbar mb-2 mb-md-0">
                <div class="btn-group">
                    <button type="button" class="btn btn-sm btn-outline-secondary">
                        <i class="bi bi-calendar3 me-1"></i>
                        {{ date('F j, Y') }}
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- BARIS PERTAMA UNTUK SENSOR (SEKARANG DENGAN GRAFIK) --}}
    <div class="row g-4 mb-4">
        {{-- Card Suhu --}}
        <div class="col-lg-4 col-md-6">
            <div class="stat-card" id="Suhu-card">
                <div class="stat-header">
                    <div class="stat-icon icon-suhu"><i class="bi bi-thermometer-half"></i></div>
                    <div class="stat-title">Suhu</div>
                </div>
                <div class="current-value-display"><span id="Suhu-current-value">Memuat...</span><span class="unit">°C</span></div>
                <div class="chart-wrapper">
                    <canvas id="suhuChart"></canvas>
                </div>
            </div>
        </div>
        {{-- Card pH --}}
        <div class="col-lg-4 col-md-6">
            <div class="stat-card" id="pH-card">
                <div class="stat-header">
                    <div class="stat-icon icon-ph"><i class="bi bi-droplet-half"></i></div>
                    <div class="stat-title">pH Air</div>
                </div>
                <div class="current-value-display"><span id="pH-current-value">Memuat...</span></div>
                <div class="chart-wrapper">
                    <canvas id="phChart"></canvas>
                </div>
            </div>
        </div>
        {{-- Card Kekeruhan --}}
        <div class="col-lg-4 col-md-6">
            <div class="stat-card" id="Kekeruhan-card">
                <div class="stat-header">
                    <div class="stat-icon icon-kekeruhan"><i class="bi bi-eye"></i></div>
                    <div class="stat-title">Kekeruhan</div>
                </div>
                <div class="current-value-display"><span id="Kekeruhan-current-value">Memuat...</span><span class="unit">NTU</span></div>
                <div class="chart-wrapper">
                    <canvas id="kekeruhanChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    {{-- BARIS KEDUA UNTUK KARTU KUALITAS AIR (TETAP SAMA) --}}
    <div class="row g-4 mb-4" id="kualitas-row">
        <div class="col-lg-12">
            <div class="stat-card" id="Kualitas-card">
                <div class="stat-header">
                    <div class="stat-icon" id="Kualitas-icon-container"><i class="bi bi-shield-check"></i></div>
                    <div class="stat-title">Kualitas Air</div>
                </div>
                <div class="stat-body">
                    <div class="stat-value" id="Kualitas-value">Menganalisis...</div>
                    <p class="stat-deskripsi" id="Kualitas-deskripsi">Menunggu data pertama dari sensor.</p>
                </div>
            </div>
        </div>
    </div>

    {{-- BARIS KETIGA UNTUK AKTUATOR/POMPA (TETAP SAMA) --}}
    <div class="row g-4" id="aktuator-row">
        {{-- Card Pompa Isi --}}
        <div class="col-lg-6 col-md-6">
            <div class="stat-card" id="PompaIsi-card">
                <div class="stat-header">
                    <div class="stat-icon icon-pompa-isi"><i class="bi bi-arrow-down-circle"></i></div>
                    <div class="stat-title">Pompa Isi</div>
                </div>
                <div class="stat-body">
                    <div class="stat-value" id="PompaIsi-value">Mati</div>
                </div>
            </div>
        </div>
        {{-- Card Pompa Buang --}}
        <div class="col-lg-6 col-md-6">
            <div class="stat-card" id="PompaBuang-card">
                <div class="stat-header">
                    <div class="stat-icon icon-pompa-buang"><i class="bi bi-arrow-up-circle"></i></div>
                    <div class="stat-title">Pompa Buang</div>
                </div>
                <div class="stat-body">
                    <div class="stat-value" id="PompaBuang-value">Mati</div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
{{-- Import Chart.js dari CDN --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script type="module">
    import { initializeApp } from "https://www.gstatic.com/firebasejs/10.12.2/firebase-app.js";
    import { getDatabase, ref, onValue } from "https://www.gstatic.com/firebasejs/10.12.2/firebase-database.js";

    // --- KONFIGURASI ---
    const firebaseConfig = @json($firebaseConfig);
    const app = initializeApp(firebaseConfig);
    const database = getDatabase(app);
    let previousData = {};
    const MAX_DATA_POINTS = 10; // PERUBAHAN: Disesuaikan menjadi 10
    const LOG_INTERVAL = 30000; // 30 detik
    let lastLogTime = 0;
    let latestSensorData = {};

    // --- ELEMEN DOM ---
    const statusBadge = document.getElementById('status-badge');
    const kualitasCard = document.getElementById('Kualitas-card');
    const kualitasIconContainer = document.getElementById('Kualitas-icon-container');
    const kualitasValue = document.getElementById('Kualitas-value');
    const kualitasDeskripsi = document.getElementById('Kualitas-deskripsi');

    // --- FUNGSI UNTUK MENYIMPAN DATA (Tidak berubah) ---
    async function logDataToDatabase(data) {
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        try {
            const response = await fetch("{{ route('sensor.log.store') }}", {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': csrfToken },
                body: JSON.stringify(data)
            });
            if (!response.ok) {
                const errorData = await response.json();
                console.error('Gagal menyimpan data:', errorData.message || 'Error tidak diketahui');
            } else {
                const result = await response.json();
                console.log('Sukses:', result.message);
            }
        } catch (error) {
            console.error('Error jaringan saat mengirim data:', error);
        }
    }

    // --- FUNGSI BANTUAN (Tidak berubah) ---
    function flashUpdate(element) {
        element.classList.add('flash-update');
        setTimeout(() => element.classList.remove('flash-update'), 700);
    }
    
    function analisisKualitasAir(suhu, ph, kekeruhan) {
        // ... (Logika analisis kualitas air tidak berubah)
        const suhuIdeal = { min: 25, max: 30 };
        const phIdeal = { min: 6.0, max: 7.5 };
        const kekeruhanIdeal = { max: 25 };
        if (suhu < suhuIdeal.min - 2 || suhu > suhuIdeal.max + 2 || ph < phIdeal.min - 1 || ph > phIdeal.max + 1 || kekeruhan > kekeruhanIdeal.max + 15) {
            return { status: "Buruk", deskripsi: "Satu atau lebih parameter di luar batas aman. Perlu tindakan segera!", iconClass: 'icon-kualitas-buruk', color: '#dc3545' };
        }
        if (suhu < suhuIdeal.min || suhu > suhuIdeal.max || ph < phIdeal.min || ph > phIdeal.max || kekeruhan > kekeruhanIdeal.max) {
            return { status: "Cukup", deskripsi: "Beberapa parameter sedikit di luar rentang ideal. Awasi kondisi air.", iconClass: 'icon-kualitas-cukup', color: '#ffc107' };
        }
        return { status: "Baik", deskripsi: "Semua parameter air dalam kondisi optimal untuk pertumbuhan ikan.", iconClass: 'icon-kualitas-baik', color: '#198754' };
    }

    // --- PENGATURAN DAN INISIALISASI GRAFIK ---
    function createChartConfig(lineColor, areaColor) {
        return { type: 'line', data: { labels: [], datasets: [{ label: 'Value', data: [], borderColor: lineColor, backgroundColor: areaColor, borderWidth: 2, pointRadius: 0, tension: 0.4, fill: true, }] }, options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { display: false }, tooltip: { enabled: false } }, scales: { x: { display: false, }, y: { display: false, } } } };
    }
    
    // Inisialisasi Chart
    const suhuChart = new Chart(document.getElementById('suhuChart').getContext('2d'), createChartConfig('rgba(253, 126, 20, 1)', 'rgba(253, 126, 20, 0.2)'));
    const phChart = new Chart(document.getElementById('phChart').getContext('2d'), createChartConfig('rgba(13, 110, 253, 1)', 'rgba(13, 110, 253, 0.2)'));
    const kekeruhanChart = new Chart(document.getElementById('kekeruhanChart').getContext('2d'), createChartConfig('rgba(111, 66, 193, 1)', 'rgba(111, 66, 193, 0.2)'));
    
    function addDataToChart(chart, label, newData) {
        chart.data.labels.push(label);
        chart.data.datasets[0].data.push(newData);
        if (chart.data.labels.length > MAX_DATA_POINTS) {
            chart.data.labels.shift();
            chart.data.datasets[0].data.shift();
        }
        chart.update('none'); // 'none' untuk animasi yang lebih halus saat live update
    }

    // =========================================================================
    // PERUBAHAN: FUNGSI BARU UNTUK MENGAMBIL DATA AWAL DARI DATABASE
    // =========================================================================
    async function initializeCharts() {
        try {
            const response = await fetch("{{ route('monitoring.initial_data') }}");
            if (!response.ok) {
                 throw new Error(`HTTP error! status: ${response.status}`);
            }
            const initialData = await response.json();

            if (initialData && initialData.length > 0) {
                // Isi grafik dengan data historis
                initialData.forEach(log => {
                    suhuChart.data.labels.push(log.label);
                    suhuChart.data.datasets[0].data.push(log.suhu);

                    phChart.data.labels.push(log.label);
                    phChart.data.datasets[0].data.push(log.ph);

                    kekeruhanChart.data.labels.push(log.label);
                    kekeruhanChart.data.datasets[0].data.push(log.kekeruhan);
                });

                // Update semua grafik sekaligus
                suhuChart.update();
                phChart.update();
                kekeruhanChart.update();

                // Ambil data paling akhir untuk ditampilkan di card
                const latestLog = initialData[initialData.length - 1];
                document.getElementById('Suhu-current-value').textContent = parseFloat(latestLog.suhu).toFixed(1);
                document.getElementById('pH-current-value').textContent = parseFloat(latestLog.ph).toFixed(1);
                document.getElementById('Kekeruhan-current-value').textContent = parseFloat(latestLog.kekeruhan).toFixed(1);
                
                // Analisis kualitas air berdasarkan data terakhir
                const kualitas = analisisKualitasAir(latestLog.suhu, latestLog.ph, latestLog.kekeruhan);
                kualitasValue.textContent = kualitas.status;
                kualitasValue.style.color = kualitas.color;
                kualitasDeskripsi.textContent = kualitas.deskripsi;
                kualitasIconContainer.className = 'stat-icon ' + kualitas.iconClass;
                kualitasIconContainer.innerHTML = '<i class="bi bi-shield-check"></i>';
            } else {
                // Jika tidak ada data awal, kosongkan teks "Memuat..."
                document.getElementById('Suhu-current-value').textContent = '--';
                document.getElementById('pH-current-value').textContent = '--';
                document.getElementById('Kekeruhan-current-value').textContent = '--';
            }

        } catch (error) {
            console.error("Gagal mengambil data awal:", error);
            // Tampilkan pesan error atau nilai default jika fetch gagal
            document.getElementById('Suhu-current-value').textContent = 'Error';
            document.getElementById('pH-current-value').textContent = 'Error';
            document.getElementById('Kekeruhan-current-value').textContent = 'Error';
        }
    }
    
    // --- LISTENER FIREBASE (DIMODIFIKASI) ---
    const dataRef = ref(database, 'Sensor');
    statusBadge.classList.add('bg-warning');

    onValue(dataRef, (snapshot) => {
        if (statusBadge.textContent !== 'Connected') {
            statusBadge.textContent = 'Connected';
            statusBadge.classList.remove('bg-warning');
            statusBadge.classList.add('bg-success');
        }
        
        const newData = snapshot.val();
        if (!newData) return;
        
        let sensorDataChanged = false;

        for (const key in newData) {
            const cardElement = document.getElementById(`${key}-card`);
            if (!cardElement) continue;

            const currentValueRaw = newData[key];
            const prevValue = previousData[key];

            if (currentValueRaw !== prevValue) {
                flashUpdate(cardElement);

                if (key === 'PompaIsi' || key === 'PompaBuang') {
                    // Logika aktuator (tidak berubah)
                    const valueElement = document.getElementById(`${key}-value`);
                    const isAktif = parseInt(currentValueRaw) === 1;
                    valueElement.textContent = isAktif ? "Aktif" : "Mati";
                    valueElement.style.color = isAktif ? '#198754' : '#dc3545';
                } else {
                    sensorDataChanged = true;
                    const currentValue = parseFloat(currentValueRaw);
                    const newLabel = new Date().toLocaleTimeString();
                    
                    switch(key) {
                        case 'Suhu':
                            document.getElementById('Suhu-current-value').textContent = currentValue.toFixed(1);
                            addDataToChart(suhuChart, newLabel, currentValue);
                            break;
                        case 'pH':
                            document.getElementById('pH-current-value').textContent = currentValue.toFixed(1);
                            addDataToChart(phChart, newLabel, currentValue);
                            break;
                        case 'Kekeruhan':
                            document.getElementById('Kekeruhan-current-value').textContent = currentValue.toFixed(1);
                            addDataToChart(kekeruhanChart, newLabel, currentValue);
                            break;
                    }
                }
            }
            previousData[key] = currentValueRaw;
        }

        if (sensorDataChanged) {
            // ... (Logika analisis kualitas dan logging tidak berubah)
            flashUpdate(kualitasCard);
            const suhu = parseFloat(newData.Suhu || previousData.Suhu || 0);
            const ph = parseFloat(newData.pH || previousData.pH || 0);
            const kekeruhan = parseFloat(newData.Kekeruhan || previousData.Kekeruhan || 0);

            const kualitas = analisisKualitasAir(suhu, ph, kekeruhan);
            
            kualitasValue.textContent = kualitas.status;
            kualitasValue.style.color = kualitas.color;
            kualitasDeskripsi.textContent = kualitas.deskripsi;
            
            kualitasIconContainer.className = 'stat-icon';
            kualitasIconContainer.classList.add(kualitas.iconClass);
            kualitasIconContainer.innerHTML = '<i class="bi bi-shield-check"></i>';

            latestSensorData = { suhu, ph, kekeruhan };
            const now = Date.now();
            if (now - lastLogTime > LOG_INTERVAL) {
                if (latestSensorData.suhu && latestSensorData.ph && latestSensorData.kekeruhan) {
                    logDataToDatabase(latestSensorData);
                    lastLogTime = now;
                }
            }
        }

    }, (error) => {
        statusBadge.textContent = 'Connection Failed';
        statusBadge.classList.remove('bg-warning', 'bg-success');
        statusBadge.classList.add('bg-danger');
        console.error("Firebase connection error:", error);
    });

    // PERUBAHAN: Panggil fungsi untuk mengisi grafik saat halaman pertama kali dimuat
    document.addEventListener('DOMContentLoaded', initializeCharts);

</script>
@endpush