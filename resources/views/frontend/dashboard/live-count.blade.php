<!DOCTYPE html>
<html lang="id" class="h-full">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>Real-Time Live Count | {{ $web_setting?->web_title ?? 'PEMILOS' }}</title>
    <meta name="description" content="Grafik Hasil Perolehan Suara Pemilihan OSIS Secara Real-Time dan Akurat">

    <!-- Favicons -->
    <link href="{{ $web_setting?->logo_image ? asset('gambar/logo/' . $web_setting->logo_image) : asset('gambar/logo-osis.png') }}" rel="icon">
    <link href="{{ $web_setting?->logo_image ? asset('gambar/logo/' . $web_setting->logo_image) : asset('gambar/logo-osis.png') }}" rel="apple-touch-icon">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700;800;900&family=Plus+Jakarta+Sans:ital,wght@0,400;0,500;0,600;0,700;0,800;1,400&display=swap" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="{{ asset('user-page/assets/vendor/bootstrap-icons/bootstrap-icons.css') }}">

    <!-- Compiled Tailwind CSS -->
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>

<body class="min-h-[100dvh] mesh-gradient-bg flex flex-col justify-between text-ocean-950 font-sans antialiased selection:bg-ocean-500 selection:text-white">
    <!-- Floating Island Nav -->
    <header class="floating-island-nav max-w-6xl">
        <a href="/" class="flex items-center gap-3 group text-decoration-none">
            <div class="w-10 h-10 rounded-2xl bg-gradient-to-br from-white to-ocean-50 border border-white/90 p-1.5 shadow-sm flex items-center justify-center transition-transform duration-300 group-hover:scale-105">
                <img src="{{ $web_setting?->logo_image ? asset('gambar/logo/' . $web_setting->logo_image) : asset('gambar/logo-osis.png') }}" 
                     alt="Logo OSIS" 
                     class="max-w-full max-h-full object-contain">
            </div>
            <div>
                <span class="font-display font-extrabold text-sm sm:text-base text-ocean-950 tracking-tight block leading-tight group-hover:text-ocean-600 transition-colors">
                    {{ $web_setting?->web_title ?? 'PEMILOS DIGITAL' }}
                </span>
                <span class="text-[10px] font-semibold text-cloudy-500 uppercase tracking-widest block">
                    Pusat Tabulasi Suara
                </span>
            </div>
        </a>

        <div class="flex items-center gap-2 sm:gap-3">
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-ocean-50 border border-ocean-200 text-ocean-800 text-xs font-bold" id="connStatusBadge">
                <span class="w-2 h-2 rounded-full bg-ocean-500 animate-pulse shadow-[0_0_8px_rgba(40,114,161,0.8)]" id="connDot"></span>
                <span id="connStatusText">WEBSOCKET INSTAN</span>
            </div>

            <button type="button" class="btn-island-secondary" id="fullscreenBtn" onclick="toggleFullscreen()" title="Layar Penuh">
                <i class="bi bi-arrows-fullscreen text-ocean-500 text-sm"></i>
                <span class="hidden sm:inline">Layar Penuh</span>
            </button>

            <a href="/" class="btn-island-secondary" title="Kembali ke Beranda">
                <i class="bi bi-house-door text-ocean-500 text-sm"></i>
                <span class="hidden sm:inline">Beranda</span>
            </a>
        </div>
    </header>

    <!-- Main Content -->
    <main class="flex-grow pt-28 pb-12 sm:pt-36 sm:pb-20 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto w-full">
        <!-- Header -->
        <div class="text-center max-w-3xl mx-auto mb-10">
            <div class="eyebrow-badge mb-3">
                <i class="bi bi-award-fill text-ocean-500"></i>
                <span>{{ $web_setting?->school_name ?? 'SMK / SMA INDONESIA' }}</span>
                <span class="text-cloudy-300">•</span>
                <span>PERIODE {{ $web_setting?->year_period ?? '2026' }} / {{ $year_plus ?? '2027' }}</span>
            </div>

            <h1 class="text-3xl sm:text-4xl lg:text-5xl font-display font-extrabold text-ocean-950 tracking-tight text-balance">
                Hasil Perolehan Suara Pemilihan OSIS
            </h1>
            
            <p class="mt-2 text-xs sm:text-sm text-cloudy-600">
                Data diperbarui secara otomatis dan langsung dari bilik suara elektronik.
            </p>

            <div class="mt-3 inline-flex items-center gap-2 text-xs text-cloudy-500 font-medium">
                <i class="bi bi-broadcast text-ocean-500" id="syncIcon"></i>
                <span>Terakhir diperbarui: <strong class="text-ocean-950 font-bold" id="lastUpdatedText">-</strong></span>
                <span id="syncModeText">• WebSocket Instan (&lt;20ms)</span>
            </div>
        </div>

        <!-- 4-Column Bento Stats Summary -->
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6 mb-8">
            <div class="bezel-card-shell">
                <div class="bezel-card-core p-4 sm:p-5">
                    <span class="text-[10px] sm:text-xs font-bold uppercase tracking-wider text-cloudy-500 flex items-center gap-1.5 mb-1">
                        <i class="bi bi-people-fill text-ocean-500"></i> Total DPT
                    </span>
                    <div class="font-display font-black text-2xl sm:text-3xl text-ocean-950 tabular-nums" id="statTotalVoters">0</div>
                    <span class="text-[11px] text-cloudy-500 mt-1 block">Hak Suara Terdaftar</span>
                </div>
            </div>

            <div class="bezel-card-shell">
                <div class="bezel-card-core p-4 sm:p-5">
                    <span class="text-[10px] sm:text-xs font-bold uppercase tracking-wider text-ocean-600 flex items-center gap-1.5 mb-1">
                        <i class="bi bi-check2-circle text-ocean-500"></i> Suara Masuk
                    </span>
                    <div class="font-display font-black text-2xl sm:text-3xl text-ocean-600 tabular-nums" id="statTotalVoted">0</div>
                    <span class="text-[11px] text-ocean-700 font-semibold mt-1 block" id="statTurnoutPct">0% Partisipasi</span>
                </div>
            </div>

            <div class="bezel-card-shell">
                <div class="bezel-card-core p-4 sm:p-5">
                    <span class="text-[10px] sm:text-xs font-bold uppercase tracking-wider text-amber-600 flex items-center gap-1.5 mb-1">
                        <i class="bi bi-hourglass-split text-amber-500"></i> Belum Memilih
                    </span>
                    <div class="font-display font-black text-2xl sm:text-3xl text-amber-600 tabular-nums" id="statTotalUnvoted">0</div>
                    <span class="text-[11px] text-cloudy-500 mt-1 block">Pemilih Belum Hadir</span>
                </div>
            </div>

            <div class="bezel-card-shell">
                <div class="bezel-card-core p-4 sm:p-5">
                    <span class="text-[10px] sm:text-xs font-bold uppercase tracking-wider text-teal-600 flex items-center gap-1.5 mb-1">
                        <i class="bi bi-pie-chart-fill text-teal-500"></i> Partisipasi
                    </span>
                    <div class="font-display font-black text-2xl sm:text-3xl text-teal-600 tabular-nums" id="statTurnoutBig">0%</div>
                    <span class="text-[11px] text-cloudy-500 mt-1 block">Persentase Kehadiran</span>
                </div>
            </div>
        </div>

        <!-- Charts Split Row -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 sm:gap-8 mb-10">
            <!-- Bar Chart (7 Cols) -->
            <div class="lg:col-span-7 bezel-card-shell">
                <div class="bezel-card-core p-5 sm:p-6 flex flex-col justify-between">
                    <div class="flex items-center justify-between pb-3 border-b border-slate-100 mb-4">
                        <div class="flex items-center gap-2">
                            <i class="bi bi-bar-chart-line-fill text-ocean-500"></i>
                            <h3 class="font-display font-bold text-sm sm:text-base text-ocean-950">Perolehan Suara Paslon</h3>
                        </div>
                        <span class="text-[10px] font-bold uppercase tracking-wider px-2.5 py-0.5 rounded-full bg-ocean-50 text-ocean-700">
                            Suara Sah
                        </span>
                    </div>
                    <div class="relative w-full h-[320px]">
                        <canvas id="liveBarChart"></canvas>
                    </div>
                </div>
            </div>

            <!-- Doughnut Chart (5 Cols) -->
            <div class="lg:col-span-5 bezel-card-shell">
                <div class="bezel-card-core p-5 sm:p-6 flex flex-col justify-between">
                    <div class="flex items-center justify-between pb-3 border-b border-slate-100 mb-4">
                        <div class="flex items-center gap-2">
                            <i class="bi bi-pie-chart-fill text-ocean-500"></i>
                            <h3 class="font-display font-bold text-sm sm:text-base text-ocean-950">Proporsi Persentase</h3>
                        </div>
                        <span class="text-[10px] font-bold uppercase tracking-wider px-2.5 py-0.5 rounded-full bg-cloudy-100 text-cloudy-700">
                            Persentase (%)
                        </span>
                    </div>
                    <div class="relative w-full h-[320px]">
                        <canvas id="livePieChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Candidate Cards Live Grid -->
        <div class="mb-6">
            <div class="text-center mb-6">
                <h3 class="font-display font-extrabold text-xl sm:text-2xl text-ocean-950">
                    Rincian Perolehan Setiap Paslon
                </h3>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6" id="candidateCardsContainer">
                <!-- Dynamically populated via JS -->
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="w-full py-6 px-4 text-center text-xs text-cloudy-400 border-t border-slate-100 bg-white/60 backdrop-blur-md">
        <div class="inline-flex items-center gap-2 flex-wrap justify-center">
            <span class="font-medium text-ocean-900/80">{{ $web_setting?->web_title ?? 'PEMILOS' }} &copy; {{ date('Y') }}</span>
            <span>•</span>
            <span class="font-medium text-ocean-900">Zidan Fathul</span>
            <span>•</span>
            <a href="https://instagram.com/zidan_kebanyakan_tugas" target="_blank" rel="noopener noreferrer" class="hover:text-pink-600 font-medium transition-colors inline-flex items-center gap-1">
                <i class="bi bi-instagram text-pink-500"></i>@zidan_kebanyakan_tugas
            </a>
        </div>
    </footer>

    <!-- Vendor Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.socket.io/4.7.5/socket.io.min.js"></script>

    <script>
        // High-end Soft Structuralism Palette
        const palette = [
            { bg: '#2872A1', border: '#1e5e86' },
            { bg: '#559bc4', border: '#3b8bc0' },
            { bg: '#184b6c', border: '#163f5b' },
            { bg: '#0d9488', border: '#0f766e' },
            { bg: '#d97706', border: '#b45309' },
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
                        maxBarThickness: 48,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    animation: { duration: 500 },
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
                    cutout: '70%',
                    animation: { duration: 500 },
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                font: { family: 'Plus Jakarta Sans', weight: '700' },
                                boxWidth: 12,
                                padding: 14
                            }
                        }
                    }
                }
            });
        }

        function updateUI(data) {
            if (!data || !data.candidates) return;

            const sum = data.summary;
            document.getElementById('statTotalVoters').textContent = sum.total_voters.toLocaleString('id-ID');
            document.getElementById('statTotalVoted').textContent = sum.total_voted.toLocaleString('id-ID');
            document.getElementById('statTotalUnvoted').textContent = sum.total_unvoted.toLocaleString('id-ID');
            document.getElementById('statTurnoutPct').textContent = `${sum.turnout_percentage}% Partisipasi`;
            document.getElementById('statTurnoutBig').textContent = `${sum.turnout_percentage}%`;
            document.getElementById('lastUpdatedText').textContent = data.last_updated;

            const labels = data.candidates.map(c => `#${c.sequence_number} ${c.name}${c.vice_name ? ' & ' + c.vice_name : ''}`);
            const votes = data.candidates.map(c => c.votes);

            barChart.data.labels = labels;
            barChart.data.datasets[0].data = votes;
            barChart.update();

            pieChart.data.labels = labels;
            pieChart.data.datasets[0].data = votes;
            pieChart.update();

            // Render Candidate Cards using Double-Bezel Architecture
            const container = document.getElementById('candidateCardsContainer');
            container.innerHTML = '';

            data.candidates.forEach((cand, idx) => {
                const color = palette[idx % palette.length].bg;
                const col = document.createElement('div');
                col.className = 'bezel-card-shell';

                const sloganHtml = cand.slogan ? `
                    <div class="italic text-xs text-ocean-800 bg-ocean-50/70 border border-ocean-100 px-3 py-1 rounded-xl mb-3">
                        “${cand.slogan}”
                    </div>
                ` : '';

                const viceHtml = cand.vice_name ? `
                    <div class="text-xs text-cloudy-600 mt-1">
                        <span class="font-bold text-ocean-950">Wakil:</span> ${cand.vice_name} 
                        <span class="text-[10px] font-bold bg-white border border-slate-200 px-1.5 py-0.5 rounded ml-1">${cand.vice_class || '-'}</span>
                    </div>
                ` : '';

                col.innerHTML = `
                    <div class="bezel-card-core p-4 sm:p-5 flex flex-col justify-between h-full">
                        <div>
                            <div class="flex items-center justify-between mb-3">
                                <span class="px-3 py-1 rounded-full bg-ocean-500 text-white font-display font-extrabold text-xs">
                                    #${cand.sequence_number}
                                </span>
                                <span class="text-xs font-bold text-white px-2.5 py-0.5 rounded-full" style="background-color: ${color};">
                                    ${cand.votes} Suara (${cand.percentage}%)
                                </span>
                            </div>

                            <div class="flex items-center gap-3 mb-3">
                                <div class="w-14 h-14 rounded-2xl bg-white border border-slate-100 p-1 flex items-center justify-center shrink-0 shadow-xs">
                                    <img src="${cand.image ? '/file/image/' + cand.image : '/gambar/logo-osis.png'}" 
                                         onerror="this.src='/gambar/logo-osis.png'" 
                                         alt="${cand.name}" 
                                         class="max-w-full max-h-full object-contain">
                                </div>
                                <div class="min-w-0 flex-grow">
                                    <h4 class="font-display font-bold text-sm sm:text-base text-ocean-950 truncate">${cand.name}</h4>
                                    <span class="text-[11px] text-cloudy-500 block">${cand.class || '-'}</span>
                                    ${viceHtml}
                                </div>
                            </div>

                            ${sloganHtml}
                        </div>

                        <div class="pt-3 border-t border-slate-100 mt-3">
                            <div class="flex justify-between text-xs font-bold text-cloudy-600 mb-1.5">
                                <span>Persentase Suara</span>
                                <span class="text-ocean-950">${cand.percentage}%</span>
                            </div>
                            <div class="w-full h-2 rounded-full bg-slate-100 overflow-hidden">
                                <div class="h-full rounded-full transition-all duration-700" style="width: ${cand.percentage}%; background-color: ${color};"></div>
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

            if (status === 'socket') {
                if (badge) {
                    badge.className = 'inline-flex items-center gap-2 px-3 py-1 rounded-full bg-ocean-50 border border-ocean-200 text-ocean-800 text-xs font-bold';
                }
                if (badgeText) badgeText.textContent = 'WEBSOCKET INSTAN';
                if (syncMode) syncMode.textContent = '• WebSocket Instan (<20ms)';
            } else if (status === 'polling') {
                if (badge) {
                    badge.className = 'inline-flex items-center gap-2 px-3 py-1 rounded-full bg-amber-50 border border-amber-200 text-amber-800 text-xs font-bold';
                }
                if (badgeText) badgeText.textContent = 'SMART POLLING';
                if (syncMode) syncMode.textContent = '• Fallback Polling (5 Detik)';
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
                        setConnectionStatus('socket');
                        if (pollingInterval) {
                            clearInterval(pollingInterval);
                            pollingInterval = null;
                        }
                    });

                    socket.on('voteUpdate', (payload) => {
                        if (payload) {
                            updateUI(payload);
                        }
                    });

                    socket.on('connect_error', () => {
                        setConnectionStatus('polling');
                        startFallbackPolling();
                    });

                    socket.on('disconnect', () => {
                        setConnectionStatus('polling');
                        startFallbackPolling();
                    });
                } catch (err) {
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
                .catch(() => {});
        }

        function toggleFullscreen() {
            if (!document.fullscreenElement) {
                document.documentElement.requestFullscreen().catch(() => {});
                document.getElementById('fullscreenBtn').innerHTML = '<i class="bi bi-fullscreen-exit text-ocean-500 text-sm"></i> <span class="hidden sm:inline">Keluar</span>';
            } else {
                document.exitFullscreen();
                document.getElementById('fullscreenBtn').innerHTML = '<i class="bi bi-arrows-fullscreen text-ocean-500 text-sm"></i> <span class="hidden sm:inline">Layar Penuh</span>';
            }
        }

        document.addEventListener('DOMContentLoaded', () => {
            initCharts();
            fetchLiveVotes();
            initRealtime();
        });
    </script>
</body>

</html>
