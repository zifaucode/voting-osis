<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>Real-Time Live Count | {{ $web_setting?->web_title ?? 'PEMILOS' }}</title>
    <meta name="description" content="Grafik Hasil Perolehan Suara Pemilihan OSIS Secara Real-Time">

    <!-- Favicons -->
    <link href="{{ $web_setting?->logo_image ? asset('gambar/logo/' . $web_setting->logo_image) : asset('gambar/logo-osis.png') }}" rel="icon">
    <link href="{{ $web_setting?->logo_image ? asset('gambar/logo/' . $web_setting->logo_image) : asset('gambar/logo-osis.png') }}" rel="apple-touch-icon">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700;800;900&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Vendor CSS Files -->
    <link rel="stylesheet" href="{{ asset('user-page/assets/vendor/bootstrap/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('user-page/assets/vendor/bootstrap-icons/bootstrap-icons.css') }}">

    <!-- Glassmorphism Stylesheet -->
    <link rel="stylesheet" href="{{ asset('css/glassmorphism.css') }}">

    <style>
        .live-pulse-badge {
            background: rgba(239, 68, 68, 0.12);
            border: 1px solid rgba(239, 68, 68, 0.35);
            color: #dc2626;
            font-size: 0.85rem;
            font-weight: 800;
            padding: 0.45rem 1.15rem;
            border-radius: 9999px;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            letter-spacing: 1px;
            text-transform: uppercase;
        }

        .red-dot {
            width: 10px;
            height: 10px;
            background-color: #ef4444;
            border-radius: 50%;
            display: inline-block;
            box-shadow: 0 0 12px #ef4444;
            animation: pulseRed 1.8s infinite;
        }

        @keyframes pulseRed {
            0% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(239, 68, 68, 0.7); }
            70% { transform: scale(1.1); box-shadow: 0 0 0 9px rgba(239, 68, 68, 0); }
            100% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(239, 68, 68, 0); }
        }

        .chart-box {
            position: relative;
            height: 340px;
            width: 100%;
        }

        .stat-card-mini {
            background: rgba(255, 255, 255, 0.82);
            backdrop-filter: blur(18px);
            -webkit-backdrop-filter: blur(18px);
            border: 1px solid rgba(255, 255, 255, 0.75);
            border-radius: 22px;
            padding: 1.25rem 1.5rem;
            box-shadow: 0 12px 30px rgba(15, 23, 42, 0.05);
            transition: all 0.3s ease;
        }

        .stat-card-mini:hover {
            transform: translateY(-4px);
            box-shadow: 0 16px 36px rgba(16, 185, 129, 0.12);
        }

        .stat-number {
            font-family: var(--font-heading);
            font-size: 2.25rem;
            font-weight: 800;
            line-height: 1.1;
            margin-bottom: 0.25rem;
        }

        .live-progress {
            height: 12px;
            border-radius: 999px;
            background: rgba(15, 23, 42, 0.08);
            overflow: hidden;
            box-shadow: inset 0 1px 2px rgba(0,0,0,0.06);
        }

        .live-progress-bar {
            height: 100%;
            border-radius: 999px;
            background: linear-gradient(90deg, #10b981, #059669);
            transition: width 0.8s cubic-bezier(0.16, 1, 0.3, 1);
        }

        .sync-indicator {
            font-size: 0.8rem;
            color: var(--text-muted);
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
        }

        .spin-icon {
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            100% { transform: rotate(360deg); }
        }
    </style>
</head>

<body>
    <div class="ambient-wrapper pb-5" style="min-height: 100vh;">
        <!-- Ambient Glowing Blobs -->
        <div class="ambient-blob blob-1"></div>
        <div class="ambient-blob blob-2"></div>
        <div class="ambient-blob blob-3"></div>

        <!-- Glass Navbar -->
        <header class="glass-navbar">
            <div class="container-fluid px-lg-5 d-flex align-items-center justify-content-between">
                <a href="/" class="brand-title">
                    <img src="{{ $web_setting?->logo_image ? asset('gambar/logo/' . $web_setting->logo_image) : asset('gambar/logo-osis.png') }}" alt="Logo OSIS">
                    <span>{{ $web_setting?->web_title ?? 'PEMILOS DIGITAL' }}</span>
                </a>

                <div class="d-flex align-items-center gap-3">
                    <div class="live-pulse-badge" id="connStatusBadge">
                        <span class="red-dot" id="connDot"></span> <span id="connStatusText">LIVE WEBSOCKET</span>
                    </div>

                    <button type="button" class="glass-btn-secondary" id="fullscreenBtn" onclick="toggleFullscreen()" title="Tampilkan Mode Layar Penuh">
                        <i class="bi bi-arrows-fullscreen"></i> <span class="d-none d-md-inline">Layar Penuh</span>
                    </button>

                    <a href="/" class="glass-btn-secondary" title="Kembali ke Beranda">
                        <i class="bi bi-house-door"></i> <span class="d-none d-md-inline">Beranda</span>
                    </a>
                </div>
            </div>
        </header>

        <!-- Main Content -->
        <main class="container-fluid px-lg-5 py-4" style="position: relative; z-index: 2;">
            <!-- Header Section -->
            <div class="text-center mb-4">
                <div class="school-badge mb-2">
                    <i class="bi bi-award-fill mr-1"></i>
                    <span>{{ $web_setting?->school_name ?? 'SMK / SMA INDONESIA' }}</span>
                    <span>•</span>
                    <span>PERIODE {{ $web_setting?->year_period ?? '2026' }} / {{ $year_plus ?? '2027' }}</span>
                </div>
                <h1 class="font-weight-bold" style="font-family: var(--font-heading); font-size: 2.5rem; color: var(--text-main); margin-bottom: 0.35rem;">
                    Hasil Perolehan Suara Pemilihan OSIS
                </h1>
                <p class="text-muted mb-2">
                    Data diperbarui secara langsung dan otomatis dari bilik suara elektronik.
                </p>
                <div class="sync-indicator">
                    <i class="bi bi-broadcast text-success" id="syncIcon"></i>
                    <span>Terakhir diperbarui: <strong id="lastUpdatedText">-</strong></span>
                    <span id="syncModeText">• WebSocket Instan (&lt;20ms)</span>
                </div>
            </div>

            <!-- Stats Bar -->
            <div class="row g-3 mb-4 justify-content-center">
                <div class="col-6 col-lg-3">
                    <div class="stat-card-mini">
                        <span class="text-muted small font-weight-bold text-uppercase letter-spacing-1 d-block mb-1">
                            <i class="bi bi-people-fill text-primary mr-1"></i> Total DPT
                        </span>
                        <div class="stat-number text-dark" id="statTotalVoters">0</div>
                        <small class="text-muted">Hak Suara Terdaftar</small>
                    </div>
                </div>
                <div class="col-6 col-lg-3">
                    <div class="stat-card-mini">
                        <span class="text-muted small font-weight-bold text-uppercase letter-spacing-1 d-block mb-1">
                            <i class="bi bi-check2-circle text-success mr-1"></i> Suara Masuk
                        </span>
                        <div class="stat-number text-success" id="statTotalVoted">0</div>
                        <small class="text-muted" id="statTurnoutPct">0% Partisipasi</small>
                    </div>
                </div>
                <div class="col-6 col-lg-3">
                    <div class="stat-card-mini">
                        <span class="text-muted small font-weight-bold text-uppercase letter-spacing-1 d-block mb-1">
                            <i class="bi bi-hourglass-split text-warning mr-1"></i> Belum Memilih
                        </span>
                        <div class="stat-number text-warning" id="statTotalUnvoted">0</div>
                        <small class="text-muted">Pemilih Belum Hadir</small>
                    </div>
                </div>
                <div class="col-6 col-lg-3">
                    <div class="stat-card-mini">
                        <span class="text-muted small font-weight-bold text-uppercase letter-spacing-1 d-block mb-1">
                            <i class="bi bi-pie-chart-fill text-info mr-1"></i> Partisipasi
                        </span>
                        <div class="stat-number text-info" id="statTurnoutBig">0%</div>
                        <small class="text-muted">Persentase Kehadiran</small>
                    </div>
                </div>
            </div>

            <!-- Visual Charts Row -->
            <div class="row g-4 mb-4">
                <!-- Bar Chart -->
                <div class="col-lg-7 mb-4">
                    <div class="glass-card p-4 h-100">
                        <div class="d-flex align-items-center justify-content-between mb-3 pb-2 border-bottom">
                            <h4 class="font-weight-bold mb-0 text-dark" style="font-size: 1.25rem;">
                                <i class="bi bi-bar-chart-line-fill text-success mr-2"></i> Perolehan Suara Pasangan Calon
                            </h4>
                            <span class="badge badge-success px-3 py-1" style="border-radius: 999px;">Jumlah Suara Sah</span>
                        </div>
                        <div class="chart-box">
                            <canvas id="liveBarChart"></canvas>
                        </div>
                    </div>
                </div>

                <!-- Doughnut Chart -->
                <div class="col-lg-5 mb-4">
                    <div class="glass-card p-4 h-100">
                        <div class="d-flex align-items-center justify-content-between mb-3 pb-2 border-bottom">
                            <h4 class="font-weight-bold mb-0 text-dark" style="font-size: 1.25rem;">
                                <i class="bi bi-pie-chart-fill text-info mr-2"></i> Persentase Suara
                            </h4>
                            <span class="badge badge-info px-3 py-1" style="border-radius: 999px;">Proporsi (%)</span>
                        </div>
                        <div class="chart-box">
                            <canvas id="livePieChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Live Candidate Cards Grid -->
            <div class="mb-4">
                <h4 class="font-weight-bold mb-3 text-dark text-center" style="font-size: 1.4rem;">
                    <i class="bi bi-person-badge-fill text-success mr-1"></i> Rincian Suara Per Paslon
                </h4>
                <div class="row g-4 justify-content-center" id="candidateCardsContainer">
                    <!-- Injected dynamically via JS -->
                </div>
            </div>
        </main>

        <!-- Footer -->
        <footer class="py-4 text-center text-muted" style="position: relative; z-index: 2; border-top: 1px solid var(--glass-border-subtle); background: rgba(255, 255, 255, 0.5); backdrop-filter: blur(10px);">
            <div class="container">
                <small class="d-block font-weight-bold text-dark mb-1">
                    {{ $web_setting?->web_title ?? 'PEMILOS' }} &copy; {{ date('Y') }} • Realtime Live Count OSIS
                </small>
                <small class="text-muted d-inline-flex align-items-center justify-content-center flex-wrap" style="gap: 0.4rem;">
                    <span><strong>Zidan Fathul</strong></span>
                    <span>•</span>
                    <a href="https://instagram.com/zidan_kebanyakan_tugas" target="_blank" rel="noopener noreferrer" class="text-decoration-none font-weight-bold d-inline-flex align-items-center" style="color: #e1306c;">
                        <i class="bi bi-instagram mr-1"></i>@zidan_kebanyakan_tugas
                    </a>
                </small>
            </div>
        </footer>
    </div>

    <!-- Vendor Scripts -->
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.socket.io/4.7.5/socket.io.min.js"></script>

    <script>
        // Palette for candidates
        const palette = [
            { bg: '#10b981', border: '#059669' }, // Emerald
            { bg: '#06b6d4', border: '#0891b2' }, // Cyan
            { bg: '#6366f1', border: '#4f46e5' }, // Indigo
            { bg: '#f59e0b', border: '#d97706' }, // Amber
            { bg: '#ec4899', border: '#db2777' }, // Pink
        ];

        let barChart = null;
        let pieChart = null;

        function initCharts() {
            const ctxBar = document.getElementById('liveBarChart').getContext('2d');
            barChart = new Chart(ctxBar, {
                type: 'bar',
                data: {
                    labels: [],
                    datasets: [{
                        label: 'Suara Sah',
                        data: [],
                        backgroundColor: palette.map(p => p.bg),
                        borderColor: palette.map(p => p.border),
                        borderWidth: 1.5,
                        borderRadius: 12,
                        maxBarThickness: 55,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    animation: { duration: 600 },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: { precision: 0, font: { family: 'Plus Jakarta Sans', weight: '600' } },
                            grid: { color: 'rgba(0,0,0,0.05)' }
                        },
                        x: {
                            ticks: { font: { family: 'Plus Jakarta Sans', weight: '700' } },
                            grid: { display: false }
                        }
                    },
                    plugins: {
                        legend: { display: false }
                    }
                }
            });

            const ctxPie = document.getElementById('livePieChart').getContext('2d');
            pieChart = new Chart(ctxPie, {
                type: 'doughnut',
                data: {
                    labels: [],
                    datasets: [{
                        data: [],
                        backgroundColor: palette.map(p => p.bg),
                        borderColor: '#ffffff',
                        borderWidth: 3,
                        hoverOffset: 6
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: '68%',
                    animation: { duration: 600 },
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                font: { family: 'Plus Jakarta Sans', weight: '700' },
                                boxWidth: 14,
                                padding: 15
                            }
                        }
                    }
                }
            });
        }

        function updateUI(data) {
            if (!data || !data.candidates) return;

            // Update Summary Stats
            const sum = data.summary;
            document.getElementById('statTotalVoters').textContent = sum.total_voters.toLocaleString('id-ID');
            document.getElementById('statTotalVoted').textContent = sum.total_voted.toLocaleString('id-ID');
            document.getElementById('statTotalUnvoted').textContent = sum.total_unvoted.toLocaleString('id-ID');
            document.getElementById('statTurnoutPct').textContent = `${sum.turnout_percentage}% Partisipasi`;
            document.getElementById('statTurnoutBig').textContent = `${sum.turnout_percentage}%`;
            document.getElementById('lastUpdatedText').textContent = data.last_updated;

            // Flash sync icon
            const syncIcon = document.getElementById('syncIcon');
            syncIcon.classList.add('spin-icon');
            setTimeout(() => syncIcon.classList.remove('spin-icon'), 800);

            // Update Charts
            const labels = data.candidates.map(c => `#${c.sequence_number} ${c.name}${c.vice_name ? ' & ' + c.vice_name : ''}`);
            const votes = data.candidates.map(c => c.votes);

            barChart.data.labels = labels;
            barChart.data.datasets[0].data = votes;
            barChart.update();

            pieChart.data.labels = labels;
            pieChart.data.datasets[0].data = votes;
            pieChart.update();

            // Render Candidate Cards
            const container = document.getElementById('candidateCardsContainer');
            container.innerHTML = '';

            data.candidates.forEach((cand, idx) => {
                const color = palette[idx % palette.length].bg;
                const col = document.createElement('div');
                col.className = 'col-md-6 col-lg-4 mb-3';
                const sloganHtml = cand.slogan ? `
                    <div class="font-italic small text-muted mb-2 px-2 py-1 rounded" style="background: rgba(0,0,0,0.03); border-left: 3px solid #10b981;">
                        “${cand.slogan}”
                    </div>
                ` : '';

                const viceHtml = cand.vice_name ? `
                    <div class="text-muted small">
                        <span class="font-weight-bold" style="color: #0284c7;">Wakil:</span> ${cand.vice_name} 
                        <span class="badge badge-light border ml-1">${cand.vice_class || '-'}</span>
                    </div>
                ` : '';

                col.innerHTML = `
                    <div class="glass-card p-3 h-100 d-flex flex-column justify-content-between">
                        <div>
                            <div class="d-flex align-items-center justify-content-between mb-2">
                                <span class="glass-badge-seq" style="font-size: 0.95rem; padding: 0.25rem 0.85rem;">
                                    Paslon #${cand.sequence_number}
                                </span>
                                <span class="badge badge-success px-2 py-1" style="font-size: 0.8rem; background-color: ${color};">
                                    ${cand.votes} Suara (${cand.percentage}%)
                                </span>
                            </div>

                            <div class="d-flex align-items-start gap-3 mb-2">
                                <img src="${cand.image ? '/file/image/' + cand.image : '/gambar/logo-osis.png'}" 
                                     onerror="this.src='/gambar/logo-osis.png'" 
                                     alt="${cand.name}" 
                                     style="width: 65px; height: 65px; object-fit: cover; border-radius: 14px; border: 2px solid #ffffff; box-shadow: 0 4px 10px rgba(0,0,0,0.08);">
                                <div class="flex-grow-1">
                                    <div class="mb-1">
                                        <span class="font-weight-bold" style="color: #059669; font-size: 0.75rem; text-transform: uppercase;">Ketua:</span>
                                        <h5 class="font-weight-bold mb-0 text-dark" style="font-size: 1.05rem;">
                                            ${cand.name} <small class="text-muted">(${cand.class || '-'})</small>
                                        </h5>
                                    </div>
                                    ${viceHtml}
                                </div>
                            </div>

                            ${sloganHtml}
                        </div>

                        <div class="mt-2 pt-2 border-top" style="border-color: rgba(0,0,0,0.05) !important;">
                            <div class="d-flex justify-content-between text-muted small font-weight-bold mb-1">
                                <span>Persentase Suara Masuk</span>
                                <span>${cand.percentage}%</span>
                            </div>
                            <div class="live-progress">
                                <div class="live-progress-bar" style="width: ${cand.percentage}%; background-color: ${color};"></div>
                            </div>
                        </div>
                    </div>
                `;
                container.appendChild(col);
            });
        }

        let socket = null;
        let pollingInterval = null;
        const SOCKET_SERVER_URL = `${window.location.protocol}//${window.location.hostname}:3001`;

        function setConnectionStatus(status) {
            const badge = document.getElementById('connStatusBadge');
            const badgeText = document.getElementById('connStatusText');
            const syncMode = document.getElementById('syncModeText');
            const syncIcon = document.getElementById('syncIcon');

            if (status === 'socket') {
                if (badge) {
                    badge.style.background = 'rgba(16, 185, 129, 0.15)';
                    badge.style.borderColor = 'rgba(16, 185, 129, 0.35)';
                    badge.style.color = '#065f46';
                }
                if (badgeText) badgeText.textContent = 'WEBSOCKET LIVE';
                if (syncMode) syncMode.textContent = '• WebSocket Instan (<20ms)';
                if (syncIcon) {
                    syncIcon.className = 'bi bi-broadcast text-success';
                }
            } else if (status === 'polling') {
                if (badge) {
                    badge.style.background = 'rgba(245, 158, 11, 0.15)';
                    badge.style.borderColor = 'rgba(245, 158, 11, 0.35)';
                    badge.style.color = '#92400e';
                }
                if (badgeText) badgeText.textContent = 'SMART POLLING';
                if (syncMode) syncMode.textContent = '• Fallback Polling (5 Detik)';
                if (syncIcon) {
                    syncIcon.className = 'bi bi-arrow-repeat text-warning';
                }
            }
        }

        function initRealtime() {
            if (typeof io !== 'undefined') {
                try {
                    socket = io(SOCKET_SERVER_URL, {
                        transports: ['websocket', 'polling'],
                        timeout: 4000,
                        reconnectionAttempts: Infinity,
                        reconnectionDelay: 2000,
                        reconnectionDelayMax: 5000,
                    });

                    socket.on('connect', () => {
                        console.log('⚡ Terhubung ke Pemilos WebSocket Broadcaster!');
                        setConnectionStatus('socket');
                        // Hentikan interval polling cadangan jika socket aktif
                        if (pollingInterval) {
                            clearInterval(pollingInterval);
                            pollingInterval = null;
                        }
                    });

                    socket.on('voteUpdate', (payload) => {
                        console.log('📡 Suara baru diterima secara instan via WebSocket:', payload);
                        if (payload) {
                            updateUI(payload);
                        }
                    });

                    socket.on('connect_error', (err) => {
                        console.warn('Socket tidak dapat terhubung, mengaktifkan smart fallback polling:', err.message);
                        setConnectionStatus('polling');
                        startFallbackPolling();
                    });

                    socket.on('disconnect', () => {
                        console.warn('Socket terputus, mengaktifkan smart fallback polling');
                        setConnectionStatus('polling');
                        startFallbackPolling();
                    });
                } catch (err) {
                    console.warn('Gagal menginisialisasi Socket.io, menggunakan polling:', err);
                    setConnectionStatus('polling');
                    startFallbackPolling();
                }
            } else {
                setConnectionStatus('polling');
                startFallbackPolling();
            }
        }

        function startFallbackPolling() {
            if (!pollingInterval) {
                // Polling cadangan dengan interval 5 detik
                pollingInterval = setInterval(fetchLiveVotes, 5000);
            }
        }

        function fetchLiveVotes() {
            axios.get('/api/live-votes')
                .then(res => {
                    if (res.data && res.data.status === 'success') {
                        updateUI(res.data);
                    }
                })
                .catch(err => {
                    console.warn('Gagal memuat polling suara:', err);
                });
        }

        function toggleFullscreen() {
            if (!document.fullscreenElement) {
                document.documentElement.requestFullscreen().catch(err => {
                    alert(`Error saat mengaktifkan layar penuh: ${err.message}`);
                });
                document.getElementById('fullscreenBtn').innerHTML = '<i class="bi bi-fullscreen-exit"></i> <span class="d-none d-md-inline">Keluar Layar Penuh</span>';
            } else {
                document.exitFullscreen();
                document.getElementById('fullscreenBtn').innerHTML = '<i class="bi bi-arrows-fullscreen"></i> <span class="d-none d-md-inline">Layar Penuh</span>';
            }
        }

        // Initialize on DOM load
        document.addEventListener('DOMContentLoaded', () => {
            initCharts();
            fetchLiveVotes(); // initial fetch
            initRealtime();   // initialize WebSocket connection
        });
    </script>
</body>

</html>
