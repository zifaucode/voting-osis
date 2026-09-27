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

    <!-- Glassmorphism & Claymorphism Stylesheet -->
    <link rel="stylesheet" href="{{ asset('css/glassmorphism.css') }}">

    <style>
        .live-pulse-badge {
            background: rgba(40, 114, 161, 0.12);
            border: 1.5px solid var(--cloudy-sky);
            color: var(--ocean-dark);
            font-size: 0.82rem;
            font-weight: 800;
            padding: 0.45rem 1.15rem;
            border-radius: var(--clay-radius-pill);
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            letter-spacing: 0.8px;
            text-transform: uppercase;
            box-shadow: 0 4px 12px rgba(40, 114, 161, 0.06);
        }

        .pulse-dot-live {
            width: 9px;
            height: 9px;
            background-color: var(--ocean-blue);
            border-radius: 50%;
            display: inline-block;
            box-shadow: 0 0 10px var(--ocean-blue);
            animation: pulseLive 1.8s infinite;
        }

        @keyframes pulseLive {
            0% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(40, 114, 161, 0.7); }
            70% { transform: scale(1.1); box-shadow: 0 0 0 8px rgba(40, 114, 161, 0); }
            100% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(40, 114, 161, 0); }
        }

        .chart-box {
            position: relative;
            height: 330px;
            width: 100%;
        }

        .stat-card-mini {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: var(--glass-blur);
            -webkit-backdrop-filter: var(--glass-blur);
            border: 1.5px solid var(--glass-border);
            border-radius: var(--clay-radius-md);
            padding: 1.25rem 1.5rem;
            box-shadow: var(--clay-shadow-card);
            transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        }

        .stat-card-mini:hover {
            transform: translateY(-4px);
            box-shadow: var(--clay-shadow-card-hover);
        }

        .stat-number {
            font-family: var(--font-heading);
            font-size: 2.25rem;
            font-weight: 800;
            line-height: 1.1;
            margin-bottom: 0.25rem;
            color: var(--ocean-navy);
            font-variant-numeric: tabular-nums;
        }

        .live-progress {
            height: 10px;
            border-radius: var(--clay-radius-pill);
            background: rgba(203, 221, 233, 0.45);
            overflow: hidden;
            box-shadow: inset 0 1px 2px rgba(14, 39, 56, 0.08);
        }

        .live-progress-bar {
            height: 100%;
            border-radius: var(--clay-radius-pill);
            background: linear-gradient(90deg, #3b8bc0, var(--ocean-blue));
            transition: width 0.8s cubic-bezier(0.16, 1, 0.3, 1);
        }

        .sync-indicator {
            font-size: 0.82rem;
            color: var(--text-muted);
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
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
    <div class="ambient-wrapper pb-5" style="min-height: 100vh; min-height: 100dvh;">
        <!-- Glass Navbar -->
        <header class="glass-navbar">
            <div class="container-fluid px-lg-5 d-flex align-items-center justify-content-between">
                <a href="/" class="brand-title">
                    <img src="{{ $web_setting?->logo_image ? asset('gambar/logo/' . $web_setting->logo_image) : asset('gambar/logo-osis.png') }}" alt="Logo OSIS">
                    <span>{{ $web_setting?->web_title ?? 'PEMILOS DIGITAL' }}</span>
                </a>

                <div class="d-flex align-items-center gap-2 gap-md-3">
                    <div class="live-pulse-badge" id="connStatusBadge">
                        <span class="pulse-dot-live" id="connDot"></span> <span id="connStatusText">LIVE WEBSOCKET</span>
                    </div>

                    <button type="button" class="glass-btn-secondary" id="fullscreenBtn" onclick="toggleFullscreen()" title="Tampilkan Mode Layar Penuh">
                        <i class="bi bi-arrows-fullscreen text-primary"></i> <span class="d-none d-md-inline">Layar Penuh</span>
                    </button>

                    <a href="/" class="glass-btn-secondary" title="Kembali ke Beranda">
                        <i class="bi bi-house-door text-primary"></i> <span class="d-none d-md-inline">Beranda</span>
                    </a>
                </div>
            </div>
        </header>

        <!-- Main Content -->
        <main class="container-fluid px-lg-5 py-4" style="position: relative; z-index: 2;">
            <!-- Header Section -->
            <div class="text-center mb-4">
                <div class="school-badge mb-2">
                    <i class="bi bi-award-fill"></i>
                    <span>{{ $web_setting?->school_name ?? 'SMK / SMA INDONESIA' }}</span>
                    <span>•</span>
                    <span>PERIODE {{ $web_setting?->year_period ?? '2026' }} / {{ $year_plus ?? '2027' }}</span>
                </div>
                <h1 class="font-weight-bold" style="font-family: var(--font-heading); font-size: 2.35rem; color: var(--ocean-navy); margin-bottom: 0.35rem;">
                    Hasil Perolehan Suara Pemilihan OSIS
                </h1>
                <p class="text-muted mb-2" style="font-size: 1.05rem;">
                    Data diperbarui secara otomatis dan langsung dari bilik suara elektronik.
                </p>
                <div class="sync-indicator">
                    <i class="bi bi-broadcast" style="color: var(--ocean-blue);" id="syncIcon"></i>
                    <span>Terakhir diperbarui: <strong id="lastUpdatedText" style="color: var(--ocean-navy);">-</strong></span>
                    <span id="syncModeText">• WebSocket Instan (&lt;20ms)</span>
                </div>
            </div>

            <!-- Stats Bar -->
            <div class="row g-3 mb-4 justify-content-center">
                <div class="col-6 col-lg-3 mb-3">
                    <div class="stat-card-mini">
                        <span class="small font-weight-bold text-uppercase d-block mb-1" style="color: var(--text-muted); letter-spacing: 0.75px;">
                            <i class="bi bi-people-fill mr-1" style="color: var(--ocean-blue);"></i> Total DPT
                        </span>
                        <div class="stat-number" id="statTotalVoters">0</div>
                        <small class="text-muted">Hak Suara Terdaftar</small>
                    </div>
                </div>
                <div class="col-6 col-lg-3 mb-3">
                    <div class="stat-card-mini">
                        <span class="small font-weight-bold text-uppercase d-block mb-1" style="color: var(--text-muted); letter-spacing: 0.75px;">
                            <i class="bi bi-check2-circle mr-1" style="color: var(--ocean-blue);"></i> Suara Masuk
                        </span>
                        <div class="stat-number" style="color: var(--ocean-blue);" id="statTotalVoted">0</div>
                        <small class="text-muted" id="statTurnoutPct">0% Partisipasi</small>
                    </div>
                </div>
                <div class="col-6 col-lg-3 mb-3">
                    <div class="stat-card-mini">
                        <span class="small font-weight-bold text-uppercase d-block mb-1" style="color: var(--text-muted); letter-spacing: 0.75px;">
                            <i class="bi bi-hourglass-split mr-1" style="color: #b45309;"></i> Belum Memilih
                        </span>
                        <div class="stat-number" style="color: #b45309;" id="statTotalUnvoted">0</div>
                        <small class="text-muted">Pemilih Belum Hadir</small>
                    </div>
                </div>
                <div class="col-6 col-lg-3 mb-3">
                    <div class="stat-card-mini">
                        <span class="small font-weight-bold text-uppercase d-block mb-1" style="color: var(--text-muted); letter-spacing: 0.75px;">
                            <i class="bi bi-pie-chart-fill mr-1" style="color: #0284c7;"></i> Partisipasi
                        </span>
                        <div class="stat-number" style="color: #0284c7;" id="statTurnoutBig">0%</div>
                        <small class="text-muted">Persentase Kehadiran</small>
                    </div>
                </div>
            </div>

            <!-- Visual Charts Row -->
            <div class="row g-4 mb-4">
                <!-- Bar Chart -->
                <div class="col-lg-7 mb-4">
                    <div class="glass-card p-4 h-100">
                        <div class="d-flex align-items-center justify-content-between mb-3 pb-2 border-bottom" style="border-color: var(--cloudy-sky-border) !important;">
                            <h4 class="font-weight-bold mb-0" style="font-size: 1.2rem; color: var(--ocean-navy);">
                                <i class="bi bi-bar-chart-line-fill mr-2" style="color: var(--ocean-blue);"></i> Perolehan Suara Pasangan Calon
                            </h4>
                            <span class="badge text-white px-3 py-1 font-weight-bold" style="border-radius: var(--clay-radius-pill); background: var(--ocean-blue); font-size: 0.8rem;">
                                Suara Sah
                            </span>
                        </div>
                        <div class="chart-box">
                            <canvas id="liveBarChart"></canvas>
                        </div>
                    </div>
                </div>

                <!-- Doughnut Chart -->
                <div class="col-lg-5 mb-4">
                    <div class="glass-card p-4 h-100">
                        <div class="d-flex align-items-center justify-content-between mb-3 pb-2 border-bottom" style="border-color: var(--cloudy-sky-border) !important;">
                            <h4 class="font-weight-bold mb-0" style="font-size: 1.2rem; color: var(--ocean-navy);">
                                <i class="bi bi-pie-chart-fill mr-2" style="color: #0284c7;"></i> Persentase Suara
                            </h4>
                            <span class="badge px-3 py-1 font-weight-bold" style="border-radius: var(--clay-radius-pill); background: var(--cloudy-sky-light); border: 1px solid var(--cloudy-sky); color: var(--ocean-dark); font-size: 0.8rem;">
                                Proporsi (%)
                            </span>
                        </div>
                        <div class="chart-box">
                            <canvas id="livePieChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Live Candidate Cards Grid -->
            <div class="mb-4">
                <h4 class="font-weight-bold mb-3 text-center" style="font-size: 1.35rem; color: var(--ocean-navy);">
                    <i class="bi bi-person-badge-fill mr-1" style="color: var(--ocean-blue);"></i> Rincian Suara Per Paslon
                </h4>
                <div class="row g-4 justify-content-center" id="candidateCardsContainer">
                    <!-- Injected dynamically via JS -->
                </div>
            </div>
        </main>

        <!-- Footer -->
        <footer class="py-4 text-center text-muted" style="position: relative; z-index: 2; border-top: 1.5px solid var(--glass-border-subtle); background: rgba(255, 255, 255, 0.65); backdrop-filter: var(--glass-blur);">
            <div class="container">
                <small class="d-block font-weight-bold mb-1" style="color: var(--ocean-navy);">
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
        // Cohesive Ocean Blue & Cloudy Sky Candidate Palette
        const palette = [
            { bg: '#2872A1', border: '#1e5e86' }, // Ocean Blue (Theme Hero)
            { bg: '#5a9cc5', border: '#3b8bc0' }, // Sky Mist Blue
            { bg: '#164969', border: '#0e2738' }, // Deep Ocean Navy
            { bg: '#0284c7', border: '#0369a1' }, // Vivid Marine
            { bg: '#d97706', border: '#b45309' }, // Golden Amber (Contrast Accent)
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
                        borderRadius: 10,
                        maxBarThickness: 50,
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
                            grid: { color: 'rgba(203, 221, 233, 0.4)' }
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
                    <div class="font-italic small mb-2 px-2 py-1 rounded" style="background: var(--cloudy-sky-light); border-left: 3px solid var(--ocean-blue); color: var(--ocean-dark);">
                        “${cand.slogan}”
                    </div>
                ` : '';

                const viceHtml = cand.vice_name ? `
                    <div class="small" style="color: var(--text-muted);">
                        <span class="font-weight-bold" style="color: var(--ocean-dark);">Wakil:</span> ${cand.vice_name} 
                        <span class="candidate-class-badge ml-1">${cand.vice_class || '-'}</span>
                    </div>
                ` : '';

                col.innerHTML = `
                    <div class="glass-card p-3 h-100 d-flex flex-column justify-content-between">
                        <div>
                            <div class="d-flex align-items-center justify-content-between mb-2">
                                <span class="glass-badge-seq" style="font-size: 0.95rem; padding: 0.25rem 0.85rem;">
                                    Paslon #${cand.sequence_number}
                                </span>
                                <span class="badge text-white px-2 py-1 font-weight-bold" style="font-size: 0.82rem; background-color: ${color}; border-radius: var(--clay-radius-pill);">
                                    ${cand.votes} Suara (${cand.percentage}%)
                                </span>
                            </div>

                            <div class="d-flex align-items-start gap-3 mb-2">
                                <img src="${cand.image ? '/file/image/' + cand.image : '/gambar/logo-osis.png'}" 
                                     onerror="this.src='/gambar/logo-osis.png'" 
                                     alt="${cand.name}" 
                                     style="width: 65px; height: 65px; object-fit: contain; border-radius: 14px; background: #ffffff; border: 1.5px solid var(--cloudy-sky); box-shadow: 0 4px 10px rgba(40, 114, 161, 0.08); padding: 4px;">
                                <div class="flex-grow-1">
                                    <div class="mb-1">
                                        <span class="font-weight-bold" style="color: var(--ocean-blue); font-size: 0.75rem; text-transform: uppercase;">Ketua:</span>
                                        <h5 class="font-weight-bold mb-0" style="font-size: 1.05rem; color: var(--ocean-navy);">
                                            ${cand.name} <small class="text-muted">(${cand.class || '-'})</small>
                                        </h5>
                                    </div>
                                    ${viceHtml}
                                </div>
                            </div>

                            ${sloganHtml}
                        </div>

                        <div class="mt-2 pt-2 border-top" style="border-color: var(--cloudy-sky-border) !important;">
                            <div class="d-flex justify-content-between text-muted small font-weight-bold mb-1">
                                <span>Persentase Suara Masuk</span>
                                <span style="color: var(--ocean-navy);">${cand.percentage}%</span>
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
                    badge.style.background = 'var(--cloudy-sky-light)';
                    badge.style.borderColor = 'var(--cloudy-sky)';
                    badge.style.color = 'var(--ocean-dark)';
                }
                if (badgeText) badgeText.textContent = 'WEBSOCKET LIVE';
                if (syncMode) syncMode.textContent = '• WebSocket Instan (<20ms)';
                if (syncIcon) {
                    syncIcon.className = 'bi bi-broadcast';
                    syncIcon.style.color = 'var(--ocean-blue)';
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
                    syncIcon.className = 'bi bi-arrow-repeat';
                    syncIcon.style.color = '#d97706';
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
                document.getElementById('fullscreenBtn').innerHTML = '<i class="bi bi-fullscreen-exit text-primary"></i> <span class="d-none d-md-inline">Keluar Layar Penuh</span>';
            } else {
                document.exitFullscreen();
                document.getElementById('fullscreenBtn').innerHTML = '<i class="bi bi-arrows-fullscreen text-primary"></i> <span class="d-none d-md-inline">Layar Penuh</span>';
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
