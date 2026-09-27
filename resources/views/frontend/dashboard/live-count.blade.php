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

    <style>
        /* ── Entrance Animations ── */
        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(22px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        @keyframes ripple {
            0%   { transform: scale(0.8); opacity: 1; }
            100% { transform: scale(2.6); opacity: 0; }
        }
        @keyframes countUp {
            from { opacity: 0.3; transform: scale(0.88); }
            to   { opacity: 1;   transform: scale(1); }
        }
        @keyframes barGrow {
            from { width: 0 !important; }
        }

        .anim-fade-up   { animation: fadeUp 0.55s cubic-bezier(0.16,1,0.3,1) both; }
        .anim-d1        { animation-delay: 0.07s; }
        .anim-d2        { animation-delay: 0.15s; }
        .anim-d3        { animation-delay: 0.24s; }
        .progress-grow  { animation: barGrow 1s cubic-bezier(0.16,1,0.3,1) both; }
        .num-flash      { animation: countUp 0.4s cubic-bezier(0.16,1,0.3,1) both; }

        /* Live pulse ring around dot */
        .live-dot {
            position: relative;
            display: inline-block;
        }
        .live-dot::before {
            content: '';
            position: absolute;
            inset: -2px;
            border-radius: 9999px;
            background: rgba(40,114,161,0.45);
            animation: ripple 2s ease-out infinite;
        }

        /* Winner card elevated treatment */
        .card-winner {
            background: linear-gradient(145deg, rgba(40,114,161,0.09) 0%, rgba(203,221,233,0.14) 100%) !important;
            box-shadow: 0 28px 60px rgba(40,114,161,0.2), 0 4px 14px rgba(15,23,42,0.05), inset 0 1px 0 rgba(255,255,255,0.9) !important;
            border-color: rgba(40,114,161,0.25) !important;
        }
        .card-winner:hover {
            box-shadow: 0 38px 80px rgba(40,114,161,0.27), 0 6px 18px rgba(15,23,42,0.06) !important;
        }

        ::-webkit-scrollbar        { width: 4px; }
        ::-webkit-scrollbar-track  { background: transparent; }
        ::-webkit-scrollbar-thumb  { background: #cbdde9; border-radius: 9999px; }
        ::-webkit-scrollbar-thumb:hover { background: #2872A1; }
    </style>
</head>

<body class="min-h-[100dvh] mesh-gradient-bg flex flex-col justify-between text-ocean-950 font-sans antialiased selection:bg-ocean-500 selection:text-white">

    <!-- ═══ Floating Island Nav ═══ -->
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
            <!-- Connection Status -->
            <div class="flex items-center gap-2">
                <span class="live-dot w-2.5 h-2.5 rounded-full bg-ocean-400 inline-block" id="connDot"></span>
                <div class="hidden sm:inline-flex items-center px-3 py-1 rounded-full bg-ocean-50 border border-ocean-200 text-ocean-800 text-xs font-bold" id="connStatusBadge">
                    <span id="connStatusText">Menghubungkan…</span>
                </div>
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

    <!-- ═══ Main ═══ -->
    <main class="flex-grow pt-28 pb-14 sm:pt-36 sm:pb-20 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto w-full">

        <!-- Page Header -->
        <div class="text-center max-w-3xl mx-auto mb-10 anim-fade-up">
            <div class="eyebrow-badge mb-3">
                <i class="bi bi-award-fill text-ocean-500"></i>
                <span>{{ $web_setting?->school_name ?? 'SMK / SMA INDONESIA' }}</span>
                <span class="text-cloudy-300">•</span>
                <span>PERIODE {{ $web_setting?->year_period ?? '2026' }} / {{ $year_plus ?? '2027' }}</span>
            </div>

            <h1 class="text-3xl sm:text-4xl lg:text-5xl font-display font-extrabold text-ocean-950 tracking-tight text-balance leading-tight">
                Hasil Perolehan Suara
                <span class="text-ocean-500">Pemilihan OSIS</span>
            </h1>

            <p class="mt-2 text-xs sm:text-sm text-cloudy-600 max-w-xl mx-auto leading-relaxed">
                Data diperbarui secara otomatis langsung dari bilik suara elektronik.
            </p>

            <!-- Live Sync Strip -->
            <div class="mt-4 inline-flex items-center gap-2.5 px-4 py-2 rounded-full bg-white/80 border border-slate-100 shadow-sm text-xs font-medium text-cloudy-600">
                <span class="relative flex w-2.5 h-2.5">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-ocean-400 opacity-60"></span>
                    <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-ocean-500"></span>
                </span>
                <span>Terakhir diperbarui:</span>
                <strong class="text-ocean-950 font-bold" id="lastUpdatedText">{{ $initial_payload['last_updated'] ?? '—' }}</strong>
                <span class="text-cloudy-400" id="syncModeText">• WebSocket Instan</span>
            </div>
        </div>

        <!-- ─── Bento Stats Row ─── -->
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-5 mb-6 anim-fade-up anim-d1">
            <!-- Total DPT -->
            <div class="bezel-card-shell">
                <div class="bezel-card-core p-4 sm:p-5 !flex-row items-start gap-3">
                    <div class="w-9 h-9 sm:w-10 sm:h-10 rounded-xl bg-ocean-50 border border-ocean-100 text-ocean-500 flex items-center justify-center text-base sm:text-lg shrink-0">
                        <i class="bi bi-people-fill"></i>
                    </div>
                    <div>
                        <div class="text-[10px] sm:text-xs font-bold uppercase tracking-wider text-cloudy-400 mb-0.5">Total DPT</div>
                        <div class="font-display font-black text-xl sm:text-3xl text-ocean-950 tabular-nums leading-none" id="statTotalVoters">{{ number_format($initial_payload['summary']['total_voters'] ?? 0, 0, ',', '.') }}</div>
                        <div class="text-[11px] text-cloudy-400 mt-1">Hak Suara Terdaftar</div>
                    </div>
                </div>
            </div>

            <!-- Suara Masuk -->
            <div class="bezel-card-shell">
                <div class="bezel-card-core p-4 sm:p-5 !flex-row items-start gap-3">
                    <div class="w-9 h-9 sm:w-10 sm:h-10 rounded-xl bg-ocean-50 border border-ocean-100 text-ocean-500 flex items-center justify-center text-base sm:text-lg shrink-0">
                        <i class="bi bi-check2-circle"></i>
                    </div>
                    <div>
                        <div class="text-[10px] sm:text-xs font-bold uppercase tracking-wider text-ocean-600 mb-0.5">Suara Masuk</div>
                        <div class="font-display font-black text-xl sm:text-3xl text-ocean-600 tabular-nums leading-none" id="statTotalVoted">{{ number_format($initial_payload['summary']['total_voted'] ?? 0, 0, ',', '.') }}</div>
                        <div class="text-[11px] text-ocean-700 font-semibold mt-1" id="statTurnoutPct">{{ $initial_payload['summary']['turnout_percentage'] ?? 0 }}% Partisipasi</div>
                    </div>
                </div>
            </div>

            <!-- Belum Memilih -->
            <div class="bezel-card-shell">
                <div class="bezel-card-core p-4 sm:p-5 !flex-row items-start gap-3">
                    <div class="w-9 h-9 sm:w-10 sm:h-10 rounded-xl bg-amber-50 border border-amber-100 text-amber-500 flex items-center justify-center text-base sm:text-lg shrink-0">
                        <i class="bi bi-hourglass-split"></i>
                    </div>
                    <div>
                        <div class="text-[10px] sm:text-xs font-bold uppercase tracking-wider text-amber-600 mb-0.5">Belum Memilih</div>
                        <div class="font-display font-black text-xl sm:text-3xl text-amber-600 tabular-nums leading-none" id="statTotalUnvoted">{{ number_format($initial_payload['summary']['total_unvoted'] ?? 0, 0, ',', '.') }}</div>
                        <div class="text-[11px] text-cloudy-400 mt-1">Pemilih Belum Hadir</div>
                    </div>
                </div>
            </div>

            <!-- Partisipasi -->
            <div class="bezel-card-shell">
                <div class="bezel-card-core p-4 sm:p-5 !flex-row items-start gap-3">
                    <div class="w-9 h-9 sm:w-10 sm:h-10 rounded-xl bg-teal-50 border border-teal-100 text-teal-500 flex items-center justify-center text-base sm:text-lg shrink-0">
                        <i class="bi bi-pie-chart-fill"></i>
                    </div>
                    <div>
                        <div class="text-[10px] sm:text-xs font-bold uppercase tracking-wider text-teal-600 mb-0.5">Partisipasi</div>
                        <div class="font-display font-black text-xl sm:text-3xl text-teal-600 tabular-nums leading-none" id="statTurnoutBig">{{ $initial_payload['summary']['turnout_percentage'] ?? 0 }}%</div>
                        <div class="text-[11px] text-cloudy-400 mt-1">Persentase Kehadiran</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- ─── Turnout Progress Bar ─── -->
        <div class="bezel-card-shell mb-8 anim-fade-up anim-d1">
            <div class="bezel-card-core p-4 sm:p-5">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-xs font-bold text-cloudy-600 flex items-center gap-1.5">
                        <i class="bi bi-bar-chart-steps text-ocean-500"></i>
                        Progress Partisipasi Pemilihan
                    </span>
                    <span class="text-xs font-bold text-ocean-950" id="turnoutLabel">{{ $initial_payload['summary']['turnout_percentage'] ?? 0 }}%</span>
                </div>
                <div class="w-full h-2.5 rounded-full bg-slate-100 overflow-hidden">
                    <div id="turnoutBar"
                         class="h-full rounded-full bg-gradient-to-r from-ocean-500 to-ocean-400 transition-all duration-700 progress-grow"
                         style="width:{{ min(100, $initial_payload['summary']['turnout_percentage'] ?? 0) }}%"></div>
                </div>
                <div class="flex justify-between text-[10px] text-cloudy-400 mt-1.5 font-medium select-none">
                    <span>0%</span><span>50%</span><span>100%</span>
                </div>
            </div>
        </div>

        <!-- ─── Charts Grid ─── -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-5 sm:gap-6 mb-10 anim-fade-up anim-d2">
            <!-- Bar Chart -->
            <div class="lg:col-span-7 bezel-card-shell">
                <div class="bezel-card-core p-5 sm:p-6">
                    <div class="flex items-center justify-between pb-3 border-b border-slate-100 mb-4">
                        <div class="flex items-center gap-2">
                            <div class="w-8 h-8 rounded-xl bg-ocean-50 text-ocean-500 flex items-center justify-center text-sm">
                                <i class="bi bi-bar-chart-line-fill"></i>
                            </div>
                            <h3 class="font-display font-bold text-sm sm:text-base text-ocean-950">Perolehan Suara Paslon</h3>
                        </div>
                        <span class="text-[10px] font-bold uppercase tracking-wider px-2.5 py-0.5 rounded-full bg-ocean-50 text-ocean-700 border border-ocean-100">Suara Sah</span>
                    </div>
                    <div class="relative w-full" style="height:300px">
                        <canvas id="liveBarChart"></canvas>
                    </div>
                </div>
            </div>

            <!-- Doughnut Chart -->
            <div class="lg:col-span-5 bezel-card-shell">
                <div class="bezel-card-core p-5 sm:p-6">
                    <div class="flex items-center justify-between pb-3 border-b border-slate-100 mb-4">
                        <div class="flex items-center gap-2">
                            <div class="w-8 h-8 rounded-xl bg-cloudy-100 text-cloudy-600 flex items-center justify-center text-sm">
                                <i class="bi bi-pie-chart-fill"></i>
                            </div>
                            <h3 class="font-display font-bold text-sm sm:text-base text-ocean-950">Proporsi Persentase</h3>
                        </div>
                        <span class="text-[10px] font-bold uppercase tracking-wider px-2.5 py-0.5 rounded-full bg-cloudy-100 text-cloudy-600 border border-cloudy-200">%</span>
                    </div>
                    <div class="relative w-full" style="height:300px">
                        <canvas id="livePieChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- ─── Candidate Cards ─── -->
        <div class="anim-fade-up anim-d3">
            <div class="flex items-center justify-between mb-6">
                <h2 class="font-display font-extrabold text-xl sm:text-2xl text-ocean-950">
                    Rincian Perolehan Setiap Paslon
                </h2>
                <div class="eyebrow-badge text-[10px]">
                    <span class="relative flex w-2 h-2">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-ocean-400 opacity-60"></span>
                        <span class="relative inline-flex rounded-full h-2 w-2 bg-ocean-500"></span>
                    </span>
                    Live Update
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 sm:gap-5" id="candidateCardsContainer">
                <!-- Loading placeholder -->
                <div class="col-span-full flex items-center justify-center py-12 text-cloudy-400">
                    <div class="text-center">
                        <div class="w-10 h-10 mx-auto mb-2.5 rounded-2xl bg-ocean-50 border border-ocean-100 text-ocean-400 flex items-center justify-center text-xl">
                            <i class="bi bi-broadcast"></i>
                        </div>
                        <p class="text-xs font-semibold">Memuat data kandidat…</p>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- ═══ Footer ═══ -->
    <footer class="w-full py-6 px-4 text-center text-xs text-cloudy-400 border-t border-slate-100 bg-white/60 backdrop-blur-md">
        <div class="inline-flex items-center gap-2 flex-wrap justify-center">
            <span class="font-medium text-ocean-900/80">{{ $web_setting?->web_title ?? 'PEMILOS' }} &copy; {{ date('Y') }}</span>
            <span>•</span>
            <span class="font-medium text-ocean-900">Zidan Fathul</span>
            <span>•</span>
            <a href="https://instagram.com/zidan_kebanyakan_tugas" target="_blank" rel="noopener noreferrer"
               class="hover:text-pink-600 font-medium transition-colors inline-flex items-center gap-1">
                <i class="bi bi-instagram text-pink-500"></i>@zidan_kebanyakan_tugas
            </a>
        </div>
    </footer>

    <!-- ═══ Scripts ═══ -->
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.socket.io/4.7.5/socket.io.min.js"></script>

    <script>
        const INITIAL_PAYLOAD = @json($initial_payload ?? null);

        // ── Palette ─────────────────────────────────────────────────────────────
        const PALETTE = [
            { bg: '#2872A1', border: '#1e5e86', light: 'rgba(240,247,251,0.9)' },
            { bg: '#0d9488', border: '#0f766e', light: 'rgba(240,253,250,0.9)' },
            { bg: '#d97706', border: '#b45309', light: 'rgba(255,251,235,0.9)' },
            { bg: '#7c3aed', border: '#6d28d9', light: 'rgba(245,243,255,0.9)' },
            { bg: '#db2777', border: '#be185d', light: 'rgba(253,242,248,0.9)' },
        ];

        let barChart = null, pieChart = null;

        // ── Init Charts ─────────────────────────────────────────────────────────
        function initCharts() {
            const baseFont = { family: 'Plus Jakarta Sans' };

            barChart = new Chart(document.getElementById('liveBarChart').getContext('2d'), {
                type: 'bar',
                data: { labels: [], datasets: [{ label: 'Suara Sah', data: [],
                    backgroundColor: PALETTE.map(p => p.bg + 'cc'),
                    borderColor: PALETTE.map(p => p.border),
                    borderWidth: 1.5, borderRadius: 10, maxBarThickness: 56 }] },
                options: {
                    responsive: true, maintainAspectRatio: false,
                    animation: { duration: 600, easing: 'easeOutQuart' },
                    scales: {
                        y: { beginAtZero: true,
                             ticks: { precision: 0, font: { ...baseFont, weight: '600' }, color: '#64748b' },
                             grid: { color: 'rgba(203,221,233,0.35)', drawBorder: false } },
                        x: { ticks: { font: { ...baseFont, weight: '700' }, color: '#0b1e2c', maxRotation: 0 },
                             grid: { display: false } }
                    },
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            backgroundColor: '#0b1e2c',
                            titleFont: { ...baseFont, weight: '700', size: 13 },
                            bodyFont: { ...baseFont, size: 12 },
                            padding: 12, cornerRadius: 12,
                            callbacks: { label: ctx => ' ' + ctx.parsed.y.toLocaleString('id-ID') + ' suara' }
                        }
                    }
                }
            });

            pieChart = new Chart(document.getElementById('livePieChart').getContext('2d'), {
                type: 'doughnut',
                data: { labels: [], datasets: [{ data: [],
                    backgroundColor: PALETTE.map(p => p.bg),
                    borderColor: '#ffffff', borderWidth: 3, hoverOffset: 8 }] },
                options: {
                    responsive: true, maintainAspectRatio: false, cutout: '68%',
                    animation: { duration: 600, easing: 'easeOutQuart' },
                    plugins: {
                        legend: { position: 'bottom', labels: {
                            font: { ...baseFont, weight: '700' },
                            boxWidth: 12, boxHeight: 12, useBorderRadius: true, borderRadius: 4,
                            padding: 16, color: '#0b1e2c' }
                        },
                        tooltip: {
                            backgroundColor: '#0b1e2c',
                            titleFont: { ...baseFont, weight: '700', size: 13 },
                            bodyFont: { ...baseFont, size: 12 }, padding: 12, cornerRadius: 12,
                            callbacks: { label: ctx => ' ' + ctx.parsed.toFixed(1) + '%' }
                        }
                    }
                }
            });
        }

        // ── Flash stat on update ─────────────────────────────────────────────────
        function flash(id) {
            const el = document.getElementById(id);
            if (!el) return;
            el.classList.remove('num-flash');
            void el.offsetWidth;
            el.classList.add('num-flash');
        }

        // ── Main UI Update ───────────────────────────────────────────────────────
        function updateUI(data, isInitial = false) {
            if (!data || !data.candidates || !data.summary) return;
            const sum = data.summary;
            const turnout = parseFloat(sum.turnout_percentage) || 0;

            const elTotalVoters  = document.getElementById('statTotalVoters');
            const elTotalVoted   = document.getElementById('statTotalVoted');
            const elTotalUnvoted = document.getElementById('statTotalUnvoted');
            const elTurnoutPct   = document.getElementById('statTurnoutPct');
            const elTurnoutBig   = document.getElementById('statTurnoutBig');
            const elLastUpdated  = document.getElementById('lastUpdatedText');
            const elTurnoutBar   = document.getElementById('turnoutBar');
            const elTurnoutLabel = document.getElementById('turnoutLabel');

            if (elTotalVoters)  elTotalVoters.textContent  = Number(sum.total_voters || 0).toLocaleString('id-ID');
            if (elTotalVoted)   elTotalVoted.textContent   = Number(sum.total_voted || 0).toLocaleString('id-ID');
            if (elTotalUnvoted) elTotalUnvoted.textContent = Number(sum.total_unvoted || 0).toLocaleString('id-ID');
            if (elTurnoutPct)   elTurnoutPct.textContent   = (sum.turnout_percentage ?? 0) + '% Partisipasi';
            if (elTurnoutBig)   elTurnoutBig.textContent   = (sum.turnout_percentage ?? 0) + '%';
            if (elLastUpdated && data.last_updated) elLastUpdated.textContent = data.last_updated;

            if (elTurnoutBar)   elTurnoutBar.style.width   = Math.min(turnout, 100) + '%';
            if (elTurnoutLabel) elTurnoutLabel.textContent = turnout.toFixed(1) + '%';

            if (!isInitial) {
                ['statTotalVoters','statTotalVoted','statTotalUnvoted','statTurnoutBig'].forEach(flash);
            }

            const maxVotes  = Math.max(...data.candidates.map(c => c.votes || 0), 0);
            const leaderIdx = data.candidates.findIndex(c => c.votes === maxVotes && c.votes > 0);

            const labels = data.candidates.map(c =>
                '#' + c.sequence_number + ' ' + c.name + (c.vice_name ? ' & ' + c.vice_name : '')
            );
            const votes  = data.candidates.map(c => c.votes || 0);
            const percs  = data.candidates.map(c => parseFloat(c.percentage) || 0);

            if (barChart) {
                barChart.data.labels = labels;
                barChart.data.datasets[0].data = votes;
                barChart.data.datasets[0].backgroundColor = PALETTE.slice(0, data.candidates.length).map((p, i) =>
                    i === leaderIdx ? p.bg : p.bg + 'a0'
                );
                barChart.update();
            }

            if (pieChart) {
                pieChart.data.labels = labels;
                pieChart.data.datasets[0].data = percs;
                pieChart.update();
            }

            renderCandidateCards(data.candidates, leaderIdx);
        }

        // ── Candidate Cards Renderer (Compact 3-in-a-row) ───────────────────────
        function renderCandidateCards(candidates, leaderIdx) {
            const container = document.getElementById('candidateCardsContainer');
            container.innerHTML = '';

            candidates.forEach((cand, idx) => {
                const color    = PALETTE[idx % PALETTE.length];
                const isWinner = idx === leaderIdx && cand.votes > 0;

                const col = document.createElement('div');
                col.className = 'bezel-card-shell relative transition-all duration-300 ' + (isWinner ? 'card-winner' : '');

                const winnerBadge = isWinner ? `
                    <div class="absolute -top-2.5 left-1/2 -translate-x-1/2 z-10 inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-white text-[10px] font-bold uppercase tracking-wider shadow-md border border-amber-300 whitespace-nowrap" style="background:linear-gradient(135deg,#f59e0b,#d97706)">
                        <i class="bi bi-trophy-fill" style="font-size:9px"></i> Unggul
                    </div>
                ` : '';

                const sloganHtml = cand.slogan ? `
                    <div class="my-1.5 px-2.5 py-1 rounded-lg border text-center font-medium italic text-[11px] truncate leading-tight" style="background:${color.light};border-color:${color.bg}25;color:${color.border}" title="${cand.slogan}">
                        &ldquo;${cand.slogan}&rdquo;
                    </div>
                ` : '';

                const viceHtml = cand.vice_name ? `
                    <div class="pt-1.5 border-t border-slate-200/60 flex items-center justify-between gap-1.5">
                        <div class="min-w-0 flex-1">
                            <span class="block text-[9px] font-bold uppercase tracking-wider text-cloudy-400">Calon Wakil</span>
                            <h4 class="font-display font-bold text-xs sm:text-sm text-ocean-950 truncate">${cand.vice_name}</h4>
                        </div>
                        <span class="text-[10px] font-bold text-ocean-900 bg-white border border-slate-200/80 px-1.5 py-0.5 rounded shrink-0">${cand.vice_class || '—'}</span>
                    </div>
                ` : '';

                col.innerHTML = `
                    ${winnerBadge}
                    <div class="bezel-card-core p-3.5 sm:p-4 flex flex-col justify-between h-full">
                        <!-- Top Header Strip -->
                        <div>
                            <div class="flex items-center justify-between gap-2 mb-2">
                                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-white font-display font-extrabold text-xs shadow-sm" style="background:${color.bg}">
                                    <i class="bi bi-star-fill text-[9px]"></i> #${cand.sequence_number}
                                </span>
                                <span class="text-xs font-bold text-ocean-950 bg-slate-100/90 border border-slate-200/80 px-2.5 py-0.5 rounded-full tabular-nums">
                                    ${parseInt(cand.votes).toLocaleString('id-ID')} Suara
                                </span>
                            </div>

                            <!-- Compact Photo Container -->
                            <div class="relative w-full h-28 sm:h-32 my-2 rounded-xl overflow-hidden border border-slate-100/90 flex items-center justify-center p-2 group" style="background:linear-gradient(135deg,${color.light},#ffffff)">
                                <img src="${cand.image ? '/file/image/' + cand.image : '/gambar/logo-osis.png'}"
                                     onerror="this.src='/gambar/logo-osis.png'"
                                     alt="${cand.name}"
                                     class="max-w-full max-h-full object-contain filter drop-shadow-sm transition-transform duration-300 group-hover:scale-105">
                            </div>

                            ${sloganHtml}

                            <!-- Candidate Details -->
                            <div class="p-2.5 my-2 rounded-xl bg-slate-50/80 border border-slate-100/90 space-y-1.5">
                                <div class="flex items-center justify-between gap-1.5">
                                    <div class="min-w-0 flex-1">
                                        <span class="block text-[9px] font-bold uppercase tracking-wider" style="color:${color.bg}">Calon Ketua</span>
                                        <h4 class="font-display font-bold text-xs sm:text-sm text-ocean-950 truncate">${cand.name}</h4>
                                    </div>
                                    <span class="text-[10px] font-bold text-ocean-900 bg-white border border-slate-200/80 px-1.5 py-0.5 rounded shrink-0">${cand.class || '—'}</span>
                                </div>
                                ${viceHtml}
                            </div>
                        </div>

                        <!-- Vote Progress Footer -->
                        <div class="pt-2 mt-auto border-t border-slate-100">
                            <div class="flex justify-between items-center text-xs mb-1">
                                <span class="text-[10px] font-bold uppercase tracking-wider text-cloudy-400">Persentase</span>
                                <span class="font-black text-xs tabular-nums" style="color:${color.bg}">${cand.percentage}%</span>
                            </div>
                            <div class="w-full h-2 rounded-full bg-slate-100 overflow-hidden">
                                <div class="h-full rounded-full progress-grow transition-all duration-700"
                                     style="width:${cand.percentage}%;background:linear-gradient(90deg,${color.bg},${color.border})"></div>
                            </div>
                        </div>
                    </div>
                `;
                container.appendChild(col);
            });
        }

        // ── Connection Status ────────────────────────────────────────────────────
        function setConnectionStatus(status) {
            const badge    = document.getElementById('connStatusBadge');
            const text     = document.getElementById('connStatusText');
            const dot      = document.getElementById('connDot');
            const syncMode = document.getElementById('syncModeText');

            if (status === 'socket') {
                badge.className   = 'hidden sm:inline-flex items-center px-3 py-1 rounded-full bg-ocean-50 border border-ocean-200 text-ocean-800 text-xs font-bold';
                dot.className     = 'live-dot w-2.5 h-2.5 rounded-full bg-ocean-500 inline-block';
                text.textContent  = 'WEBSOCKET INSTAN';
                syncMode.textContent = '• WebSocket Instan (<20ms)';
            } else if (status === 'polling') {
                badge.className   = 'hidden sm:inline-flex items-center px-3 py-1 rounded-full bg-amber-50 border border-amber-200 text-amber-800 text-xs font-bold';
                dot.className     = 'w-2.5 h-2.5 rounded-full bg-amber-400 inline-block animate-pulse';
                text.textContent  = 'POLLING AKTIF';
                syncMode.textContent = '• Fallback Polling (5 Detik)';
            }
        }

        // ── Realtime Init ────────────────────────────────────────────────────────
        let socket = null, pollingInterval = null;
        const SOCKET_URL = window.location.protocol + '//' + window.location.hostname + ':3001';

        function initRealtime() {
            if (typeof io !== 'undefined') {
                try {
                    socket = io(SOCKET_URL, {
                        transports: ['websocket', 'polling'],
                        timeout: 4000,
                        reconnectionAttempts: Infinity,
                        reconnectionDelay: 2000,
                        reconnectionDelayMax: 5000,
                    });
                    socket.on('connect',       () => { setConnectionStatus('socket'); if (pollingInterval) { clearInterval(pollingInterval); pollingInterval = null; } });
                    socket.on('voteUpdate',    payload => { if (payload) updateUI(payload); });
                    socket.on('connect_error', () => { setConnectionStatus('polling'); startFallbackPolling(); });
                    socket.on('disconnect',    () => { setConnectionStatus('polling'); startFallbackPolling(); });
                } catch { setConnectionStatus('polling'); startFallbackPolling(); }
            } else {
                setConnectionStatus('polling');
                startFallbackPolling();
            }
        }

        function startFallbackPolling() {
            if (!pollingInterval) pollingInterval = setInterval(fetchLiveVotes, 5000);
        }

        function fetchLiveVotes() {
            axios.get('/api/live-votes')
                .then(res => { if (res.data && res.data.status === 'success') updateUI(res.data); })
                .catch(() => {});
        }

        // ── Fullscreen ───────────────────────────────────────────────────────────
        function toggleFullscreen() {
            const btn = document.getElementById('fullscreenBtn');
            if (!document.fullscreenElement) {
                document.documentElement.requestFullscreen().catch(() => {});
                btn.innerHTML = '<i class="bi bi-fullscreen-exit text-ocean-500 text-sm"></i><span class="hidden sm:inline">Keluar</span>';
            } else {
                document.exitFullscreen();
                btn.innerHTML = '<i class="bi bi-arrows-fullscreen text-ocean-500 text-sm"></i><span class="hidden sm:inline">Layar Penuh</span>';
            }
        }

        // ── Boot ─────────────────────────────────────────────────────────────────
        document.addEventListener('DOMContentLoaded', () => {
            initCharts();
            if (INITIAL_PAYLOAD && INITIAL_PAYLOAD.candidates) {
                updateUI(INITIAL_PAYLOAD, true);
            }
            initRealtime();
            fetchLiveVotes();
        });
    </script>
</body>
</html>
