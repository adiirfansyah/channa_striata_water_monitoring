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
        margin-bottom: 1rem;
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

    .icon-suhu { background-color: #fd7e14; }
    .icon-ph { background-color: #0d6efd; }
    .icon-kekeruhan { background-color: #6f42c1; }
    .icon-kondisi-ideal { background-color: #198754; }
    .icon-kondisi-tidak-ideal { background-color: #dc3545; }

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
        height: 150px;
        margin-top: 1rem;
    }

    .current-value-display {
        font-size: 1.75rem;
        font-weight: 600;
        color: #343a40;
        text-align: right;
        margin-bottom: -1.25rem;
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
    .col-lg-4, .col-lg-12 {
        animation: fadeIn 0.5s ease-out forwards;
        opacity: 0;
    }
    
    .col-lg-4:nth-child(1) { animation-delay: 0.1s; }
    .col-lg-4:nth-child(2) { animation-delay: 0.2s; }
    .col-lg-4:nth-child(3) { animation-delay: 0.3s; }
    #kondisi-row .col-lg-12 { animation-delay: 0.4s; }

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

    {{-- BARIS PERTAMA KARTU SENSOR --}}
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
    
    {{-- BARIS KEDUA UNTUK KARTU KONDISI AIR --}}
    <div class="row g-4 mb-4" id="kondisi-row">
        <div class="col-lg-12">
            <div class="stat-card" id="KondisiAir-card">
                <div class="stat-header">
                    <div class="stat-icon" id="KondisiAir-icon-container"><i class="bi bi-water"></i></div>
                    <div class="stat-title">Kondisi Air</div>
                </div>
                <div class="stat-body">
                    <div class="stat-value" id="KondisiAir-value">Menganalisis...</div>
                    <p class="stat-deskripsi" id="KondisiAir-deskripsi">Menunggu data dari sensor.</p>
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
    const MAX_DATA_POINTS = 10;
    const LOG_INTERVAL = 30000;
    let lastLogTime = 0;

    // --- ELEMEN DOM ---
    const statusBadge = document.getElementById('status-badge');

    // --- FUNGSI UNTUK MENYIMPAN DATA & BANTUAN ---
    async function logDataToDatabase(data) {
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        try {
            const response = await fetch("{{ route('sensor.log.store') }}", {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': csrfToken },
                body: JSON.stringify(data)
            });
            if (response.ok) console.log('Sukses:', (await response.json()).message);
            else console.error('Gagal menyimpan data:', await response.json());
        } catch (error) {
            console.error('Error jaringan saat mengirim data:', error);
        }
    }

    function flashUpdate(element) {
        element.classList.add('flash-update');
        setTimeout(() => element.classList.remove('flash-update'), 700);
    }

    // --- PENGATURAN GRAFIK DENGAN SUMBU Y ---
    function createChartConfig(lineColor, areaColor, suggestedMin, suggestedMax) {
        return {
            type: 'line',
            data: {
                labels: [],
                datasets: [{
                    label: 'Value',
                    data: [],
                    borderColor: lineColor,
                    backgroundColor: areaColor,
                    borderWidth: 2,
                    pointRadius: 0,
                    tension: 0.4,
                    fill: true,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false }, tooltip: { enabled: false } },
                scales: {
                    x: { display: false },
                    y: {
                        display: true,
                        grace: '5%',
                        ticks: {
                            font: { family: "'Poppins', sans-serif", size: 10 },
                            color: '#6c757d'
                        },
                        grid: {
                            drawBorder: false,
                            color: '#e9ecef',
                            borderDash: [3, 3],
                        },
                        suggestedMin: suggestedMin,
                        suggestedMax: suggestedMax
                    }
                }
            }
        };
    }
    
    const suhuChart = new Chart(document.getElementById('suhuChart').getContext('2d'), createChartConfig('rgba(253, 126, 20, 1)', 'rgba(253, 126, 20, 0.2)', 20, 40));
    const phChart = new Chart(document.getElementById('phChart').getContext('2d'), createChartConfig('rgba(13, 110, 253, 1)', 'rgba(13, 110, 253, 0.2)', 4, 10));
    const kekeruhanChart = new Chart(document.getElementById('kekeruhanChart').getContext('2d'), createChartConfig('rgba(111, 66, 193, 1)', 'rgba(111, 66, 193, 0.2)', 0, 100));
    
    function addDataToChart(chart, label, newData) {
        chart.data.labels.push(label);
        chart.data.datasets[0].data.push(newData);
        if (chart.data.labels.length > MAX_DATA_POINTS) {
            chart.data.labels.shift();
            chart.data.datasets[0].data.shift();
        }
        chart.update('none');
    }

    async function initializeCharts() {
        try {
            const response = await fetch("{{ route('monitoring.initial_data') }}");
            if (!response.ok) throw new Error(`HTTP error! status: ${response.status}`);
            const initialData = await response.json();
            if (initialData && initialData.length > 0) {
                initialData.forEach(log => {
                    suhuChart.data.labels.push(log.label); suhuChart.data.datasets[0].data.push(log.suhu);
                    phChart.data.labels.push(log.label); phChart.data.datasets[0].data.push(log.ph);
                    kekeruhanChart.data.labels.push(log.label); kekeruhanChart.data.datasets[0].data.push(log.kekeruhan);
                });
                suhuChart.update(); phChart.update(); kekeruhanChart.update();
                const latestLog = initialData[initialData.length - 1];
                document.getElementById('Suhu-current-value').textContent = parseFloat(latestLog.suhu).toFixed(1);
                document.getElementById('pH-current-value').textContent = parseFloat(latestLog.ph).toFixed(1);
                document.getElementById('Kekeruhan-current-value').textContent = parseFloat(latestLog.kekeruhan).toFixed(1);
            } else {
                document.getElementById('Suhu-current-value').textContent = '--';
                document.getElementById('pH-current-value').textContent = '--';
                document.getElementById('Kekeruhan-current-value').textContent = '--';
            }
        } catch (error) {
            console.error("Gagal mengambil data awal:", error);
            document.getElementById('Suhu-current-value').textContent = 'Error';
            document.getElementById('pH-current-value').textContent = 'Error';
            document.getElementById('Kekeruhan-current-value').textContent = 'Error';
        }
    }
    
    // --- LISTENER FIREBASE ---
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

        let sensorDataChangedForLog = false;

        for (const key in newData) {
            if (newData[key] === previousData[key]) continue;

            if (key === 'Suhu' || key === 'pH' || key === 'Kekeruhan') {
                sensorDataChangedForLog = true;
                const cardElement = document.getElementById(`${key}-card`);
                if (!cardElement) continue;

                flashUpdate(cardElement);
                const currentValue = parseFloat(newData[key]);
                const newLabel = new Date().toLocaleTimeString();
                document.getElementById(`${key}-current-value`).textContent = currentValue.toFixed(1);

                if (key === 'Suhu') addDataToChart(suhuChart, newLabel, currentValue);
                if (key === 'pH') addDataToChart(phChart, newLabel, currentValue);
                if (key === 'Kekeruhan') addDataToChart(kekeruhanChart, newLabel, currentValue);

            } else if (key === 'Kondisi_air') {
                const cardElement = document.getElementById('KondisiAir-card');
                const valueElement = document.getElementById('KondisiAir-value');
                const deskripsiElement = document.getElementById('KondisiAir-deskripsi');
                const iconContainer = document.getElementById('KondisiAir-icon-container');
                if (!cardElement || !valueElement || !deskripsiElement || !iconContainer) continue;

                flashUpdate(cardElement);
                const isIdeal = parseInt(newData[key]) === 1;

                if (isIdeal) {
                    valueElement.textContent = 'Ideal';
                    valueElement.style.color = '#198754';
                    deskripsiElement.textContent = 'Kondisi air saat ini sesuai untuk budidaya.';
                    iconContainer.className = 'stat-icon icon-kondisi-ideal';
                } else {
                    valueElement.textContent = 'Tidak Ideal';
                    valueElement.style.color = '#dc3545';
                    deskripsiElement.textContent = 'Kondisi air tidak sesuai, perlu perhatian.';
                    iconContainer.className = 'stat-icon icon-kondisi-tidak-ideal';
                }
            }
            previousData[key] = newData[key];
        }

        if (sensorDataChangedForLog) {
            const now = Date.now();
            if (now - lastLogTime > LOG_INTERVAL) {
                const latestSensorData = { 
                    suhu: parseFloat(newData.Suhu || previousData.Suhu || 0), 
                    ph: parseFloat(newData.pH || previousData.pH || 0), 
                    kekeruhan: parseFloat(newData.Kekeruhan || previousData.Kekeruhan || 0) 
                };
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

    document.addEventListener('DOMContentLoaded', initializeCharts);

</script>
@endpush