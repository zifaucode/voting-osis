@extends('layouts-admin.app')
@section('title')
Dashboard Admin | {{ $web_setting?->web_title ?? 'Pemilos' }}
@endsection

@section('head')
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Outfit:wght@500;600;700;800;900&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

<style>
    /* ── Tabular nums ── */
    .tabular-nums { font-variant-numeric: tabular-nums; }

    /* ── Live sync micro-dot ── */
    @keyframes pulseLive {
        0%, 100% { opacity: 1; transform: scale(1); }
        50%       { opacity: 0.4; transform: scale(1.18); }
    }
    .live-dot {
        width: 8px; height: 8px;
        background: #10b981;
        border-radius: 50%;
        display: inline-block;
        box-shadow: 0 0 10px rgba(16, 185, 129, 0.75);
        animation: pulseLive 2s ease-in-out infinite;
    }

    /* ── Entrance animation ── */
    @keyframes slideUp {
        from { opacity: 0; transform: translateY(18px); }
        to   { opacity: 1; transform: translateY(0); }
    }
    .anim-up    { animation: slideUp 0.48s cubic-bezier(0.16,1,0.3,1) both; }
    .anim-up-d1 { animation-delay: 0.06s; }
    .anim-up-d2 { animation-delay: 0.13s; }
    .anim-up-d3 { animation-delay: 0.21s; }
    .anim-up-d4 { animation-delay: 0.30s; }

    /* ── Progress bar ── */
    @keyframes growBar {
        from { width: 0 !important; }
    }
    .bar-grow { animation: growBar 0.9s cubic-bezier(0.16,1,0.3,1) both 0.3s; }

    /* ── Winner glow on leading candidate card ── */
    .candidate-winner {
        background: linear-gradient(145deg, rgba(40,114,161,0.10) 0%, rgba(203,221,233,0.16) 100%) !important;
        border-color: rgba(40,114,161,0.3) !important;
        box-shadow: 0 6px 24px rgba(40,114,161,0.18), inset 0 1px 0 rgba(255,255,255,0.9) !important;
    }

    /* ── Stat flash on update ── */
    @keyframes statFlash {
        from { opacity: 0.35; transform: scale(0.88); }
        to   { opacity: 1;    transform: scale(1); }
    }
    .stat-flash { animation: statFlash 0.38s cubic-bezier(0.16,1,0.3,1) both; }

    /* ── Election status badge colors ── */
    .status-buka  { background: rgba(16,185,129,0.12); border-color: rgba(16,185,129,0.3); color: #059669; }
    .status-tutup { background: rgba(239,68,68,0.1);   border-color: rgba(239,68,68,0.25); color: #dc2626; }
    .status-siap  { background: rgba(245,158,11,0.1);  border-color: rgba(245,158,11,0.25); color: #d97706; }
</style>
@endsection

@section('content')

<div id="app" v-cloak class="min-h-screen px-4 sm:px-6 lg:px-8 py-6 sm:py-8">
    <div class="max-w-7xl mx-auto space-y-7">

        <!-- ── Page Header ── -->
        <div class="flex flex-col md:flex-row md:items-start md:justify-between gap-5 anim-up">
            <div>
                <div class="flex items-center gap-2 mb-2 flex-wrap">
                    <span class="eyebrow-badge">
                        <i class="fas fa-satellite-dish text-ocean-500"></i>
                        <span>Konsol Administrator</span>
                    </span>
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold border" :class="isSocketConnected ? 'status-buka' : 'status-siap'">
                        <span class="live-dot"></span>
                        <span class="tabular-nums">Live Sync: @{{ lastUpdated }}</span>
                    </span>

                    <!-- Election Status -->
                    @php
                        $ws = $web_setting;
                        $now = now();
                        $electionStatus = 'siap';
                        if ($ws && $ws->start_date && $ws->end_time) {
                            if ($now->between(\Carbon\Carbon::parse($ws->start_date), \Carbon\Carbon::parse($ws->end_time))) {
                                $electionStatus = 'buka';
                            } elseif ($now->gt(\Carbon\Carbon::parse($ws->end_time))) {
                                $electionStatus = 'tutup';
                            }
                        }
                    @endphp
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold border status-{{ $electionStatus }}">
                        @if($electionStatus === 'buka')
                            <i class="fas fa-play-circle"></i> Pemilihan Berlangsung
                        @elseif($electionStatus === 'tutup')
                            <i class="fas fa-stop-circle"></i> Pemilihan Ditutup
                        @else
                            <i class="fas fa-clock"></i> Menunggu Jadwal
                        @endif
                    </span>
                </div>

                <h1 class="font-display font-extrabold text-2xl sm:text-3xl text-ocean-950 tracking-tight">
                    Dashboard Pemilihan OSIS
                </h1>
                <p class="text-sm text-slate-500 mt-1">
                    Pemantauan realtime hasil pemungutan suara, statistik DPT, dan perolehan kandidat.
                </p>
            </div>

            <!-- Header Actions -->
            <div class="flex flex-wrap items-center gap-3 shrink-0">
                <button @click="confirmResetAllVotes" type="button"
                        class="inline-flex items-center gap-2 px-4 py-2.5 rounded-full bg-rose-50 hover:bg-rose-100 text-rose-700 font-display font-bold text-xs sm:text-sm border border-rose-200/80 active:scale-[0.98] transition-all duration-200 shadow-sm">
                    <i class="fas fa-trash-restore text-rose-500"></i>
                    <span>Reset Uji Coba</span>
                </button>
                <a href="/live-count" target="_blank" class="btn-island-primary">
                    <span>Buka Live Count</span>
                    <div class="btn-island-icon">
                        <i class="fas fa-external-link-alt text-xs"></i>
                    </div>
                </a>
            </div>
        </div>

        <!-- ── 4-Column Metric Strip ── -->
        <div class="grid grid-cols-2 xl:grid-cols-4 gap-4 sm:gap-5 anim-up anim-up-d1">

            <!-- DPT -->
            <div class="bezel-card-shell">
                <div class="bezel-card-core p-4 sm:p-5 !flex-row items-start gap-3">
                    <div class="w-10 h-10 rounded-xl bg-ocean-50 text-ocean-500 flex items-center justify-center border border-ocean-100 shrink-0 text-base sm:text-lg">
                        <i class="fas fa-users"></i>
                    </div>
                    <div class="min-w-0">
                        <div class="text-[10px] font-bold tracking-wider uppercase text-slate-400 mb-0.5">Total DPT</div>
                        <div class="font-display font-extrabold text-xl sm:text-3xl text-ocean-950 tabular-nums leading-none" id="metTotalVoters">
                            @{{ stats.total_voters.toLocaleString('id-ID') }}
                        </div>
                        <div class="text-[11px] text-slate-400 mt-1">Pemilih Terdaftar</div>
                    </div>
                </div>
            </div>

            <!-- Suara Masuk -->
            <div class="bezel-card-shell">
                <div class="bezel-card-core p-4 sm:p-5 !flex-row items-start gap-3">
                    <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center border border-emerald-100 shrink-0 text-base sm:text-lg">
                        <i class="fas fa-vote-yea"></i>
                    </div>
                    <div class="min-w-0">
                        <div class="text-[10px] font-bold tracking-wider uppercase text-emerald-600 mb-0.5">Suara Masuk</div>
                        <div class="font-display font-extrabold text-xl sm:text-3xl text-emerald-600 tabular-nums leading-none" id="metTotalVoted">
                            @{{ stats.total_voted.toLocaleString('id-ID') }}
                        </div>
                        <div class="text-[11px] text-emerald-700 font-semibold mt-1">@{{ stats.turnout_percentage }}% Partisipasi</div>
                    </div>
                </div>
            </div>

            <!-- Belum Memilih -->
            <div class="bezel-card-shell">
                <div class="bezel-card-core p-4 sm:p-5 !flex-row items-start gap-3">
                    <div class="w-10 h-10 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center border border-amber-100 shrink-0 text-base sm:text-lg">
                        <i class="fas fa-user-clock"></i>
                    </div>
                    <div class="min-w-0">
                        <div class="text-[10px] font-bold tracking-wider uppercase text-amber-600 mb-0.5">Belum Memilih</div>
                        <div class="font-display font-extrabold text-xl sm:text-3xl text-amber-600 tabular-nums leading-none" id="metTotalUnvoted">
                            @{{ stats.total_unvoted.toLocaleString('id-ID') }}
                        </div>
                        <div class="text-[11px] text-slate-400 mt-1">Hak Suara Tersisa</div>
                    </div>
                </div>
            </div>

            <!-- Partisipasi % -->
            <div class="bezel-card-shell">
                <div class="bezel-card-core p-4 sm:p-5 !flex-row items-start gap-3">
                    <div class="w-10 h-10 rounded-xl bg-ocean-50 text-ocean-500 flex items-center justify-center border border-ocean-100 shrink-0 text-base sm:text-lg">
                        <i class="fas fa-chart-pie"></i>
                    </div>
                    <div class="min-w-0">
                        <div class="text-[10px] font-bold tracking-wider uppercase text-ocean-600 mb-0.5">Partisipasi</div>
                        <div class="font-display font-extrabold text-xl sm:text-3xl text-ocean-600 tabular-nums leading-none" id="metTurnout">
                            @{{ stats.turnout_percentage }}%
                        </div>
                        <div class="text-[11px] text-slate-400 mt-1">Persentase Kehadiran</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- ── Turnout Progress Bar ── -->
        <div class="bezel-card-shell anim-up anim-up-d1">
            <div class="bezel-card-core p-4 sm:p-5">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-xs font-bold text-slate-500 flex items-center gap-1.5">
                        <i class="fas fa-tasks text-ocean-500"></i>
                        Progress Partisipasi Keseluruhan
                    </span>
                    <span class="text-xs font-bold text-ocean-950 tabular-nums" id="turnoutPct">@{{ stats.turnout_percentage }}%</span>
                </div>
                <div class="w-full h-2.5 bg-slate-100 rounded-full overflow-hidden">
                    <div id="turnoutBar"
                         class="h-2.5 rounded-full bg-gradient-to-r from-ocean-500 to-emerald-500 bar-grow transition-all duration-700"
                         :style="'width: ' + Math.min(stats.turnout_percentage, 100) + '%'">
                    </div>
                </div>
                <div class="flex justify-between text-[10px] text-slate-400 mt-1.5 font-medium select-none">
                    <span>0%</span><span>50%</span><span>100%</span>
                </div>
            </div>
        </div>

        <!-- ── Candidate Cards ── -->
        <div class="anim-up anim-up-d2">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h2 class="font-display font-extrabold text-xl text-ocean-950 flex items-center gap-2">
                        <span class="w-2.5 h-2.5 rounded-full bg-ocean-500 inline-block"></span>
                        Perolehan Suara Pasangan Calon
                    </h2>
                    <p class="text-xs text-slate-500 mt-0.5 ml-4.5">Distribusi suara realtime per kandidat Ketua &amp; Wakil Ketua OSIS.</p>
                </div>
                <a href="/admin/osis-candidate" class="btn-island-secondary">
                    <i class="fas fa-id-card text-ocean-500"></i>
                    <span class="hidden sm:inline">Kelola Paslon</span>
                </a>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
                <div v-for="(candidate, idx) in osisCandidate" :key="candidate.id"
                     class="group relative rounded-3xl border p-5 transition-all duration-300 ease-spring flex flex-col justify-between"
                     :class="isLeader(candidate) ? 'candidate-winner' : 'bg-slate-50/70 border-slate-200/80 hover:bg-white hover:border-ocean-300 hover:shadow-bezel-hover'">

                    <!-- Winner badge -->
                    <div v-if="isLeader(candidate) && candidate.total > 0"
                         class="absolute -top-3 left-1/2 -translate-x-1/2 inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-white text-[10px] font-bold uppercase tracking-wider shadow-lg border border-amber-300 whitespace-nowrap z-10"
                         style="background:linear-gradient(135deg,#f59e0b,#d97706)">
                        <i class="fas fa-trophy" style="font-size:9px"></i> Sementara Unggul
                    </div>

                    <div>
                        <!-- Sequence & Class Pills -->
                        <div class="flex items-center justify-between mb-4">
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-display font-extrabold bg-ocean-500 text-white shadow-sm">
                                <span>No. Urut</span>
                                <span class="tabular-nums text-sm">@{{ candidate.sequence_number }}</span>
                            </span>
                            <span class="px-2.5 py-1 rounded-full text-xs font-semibold bg-white border border-slate-200 text-slate-600">
                                @{{ candidate.class || '—' }}
                            </span>
                        </div>

                        <!-- Photo -->
                        <div class="relative aspect-[4/3] rounded-2xl overflow-hidden bg-slate-100 border border-slate-200/90 shadow-inner mb-4 group-hover:scale-[1.01] transition-transform duration-300">
                            <img :src="'/file/image/' + candidate.image"
                                 onerror="this.src='/gambar/logo-osis.png'"
                                 class="w-full h-full object-cover object-top"
                                 :alt="candidate.name">
                            <div class="absolute inset-0 bg-gradient-to-t from-black/35 via-transparent to-transparent"></div>
                        </div>

                        <!-- Names -->
                        <div class="text-center px-1">
                            <h3 class="font-display font-extrabold text-base sm:text-lg text-ocean-950 tracking-tight leading-tight">
                                @{{ candidate.name }}
                            </h3>
                            <div v-if="candidate.vice_name" class="text-xs text-slate-600 mt-1 flex items-center justify-center gap-1.5 flex-wrap">
                                <span>Wakil:</span>
                                <span class="font-bold text-ocean-900">@{{ candidate.vice_name }}</span>
                                <span class="px-1.5 py-0.5 rounded bg-slate-200/70 text-[10px] text-slate-700 font-medium">@{{ candidate.vice_class }}</span>
                            </div>

                            <!-- Slogan -->
                            <div v-if="candidate.slogan" class="mt-2.5 p-2 rounded-xl bg-white border border-slate-100 text-xs italic text-slate-500 shadow-sm text-center">
                                &ldquo;@{{ candidate.slogan }}&rdquo;
                            </div>
                        </div>
                    </div>

                    <!-- Vote Progress Footer -->
                    <div class="mt-5 pt-4 border-t border-slate-200/80">
                        <div class="flex items-baseline justify-between mb-2">
                            <span class="text-xs font-semibold text-slate-500">Suara Terkumpul:</span>
                            <div class="flex items-baseline gap-1">
                                <span class="font-display font-extrabold text-xl tabular-nums"
                                      :class="isLeader(candidate) && candidate.total > 0 ? 'text-ocean-600' : 'text-slate-700'">
                                    @{{ (candidate.total || 0).toLocaleString('id-ID') }}
                                </span>
                                <span class="text-xs font-semibold text-slate-400">suara</span>
                            </div>
                        </div>

                        <div class="w-full bg-slate-200/80 rounded-full h-2.5 overflow-hidden">
                            <div class="h-2.5 rounded-full transition-all duration-700"
                                 :class="isLeader(candidate) && candidate.total > 0 ? 'bg-gradient-to-r from-ocean-500 to-ocean-400' : 'bg-slate-400'"
                                 :style="'width: ' + (candidate.percentase || 0) + '%'">
                            </div>
                        </div>

                        <div class="flex items-center justify-between text-[11px] font-semibold text-slate-500 mt-1.5">
                            <span>Persentase DPT:</span>
                            <span class="font-bold tabular-nums" :class="isLeader(candidate) && candidate.total > 0 ? 'text-ocean-600' : 'text-ocean-950'">
                                @{{ Number(candidate.percentase || 0).toFixed(1) }}%
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- ── Charts Grid ── -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-5 anim-up anim-up-d3">

            <!-- Bar Chart -->
            <div class="lg:col-span-7 bezel-card-shell">
                <div class="bezel-card-core p-5 sm:p-6">
                    <div class="flex items-center justify-between mb-4 pb-3 border-b border-slate-100">
                        <div class="flex items-center gap-2">
                            <div class="w-8 h-8 rounded-xl bg-ocean-50 text-ocean-500 flex items-center justify-center text-sm">
                                <i class="fas fa-chart-bar"></i>
                            </div>
                            <h3 class="font-display font-bold text-base text-ocean-950">Grafik Perolehan Suara</h3>
                        </div>
                        <span class="text-[10px] font-bold uppercase tracking-wider px-2.5 py-0.5 rounded-full bg-ocean-50 text-ocean-700 border border-ocean-100">Batang</span>
                    </div>
                    <div class="relative w-full" style="height:300px">
                        <canvas id="myChart"></canvas>
                    </div>
                </div>
            </div>

            <!-- Doughnut Chart -->
            <div class="lg:col-span-5 bezel-card-shell">
                <div class="bezel-card-core p-5 sm:p-6">
                    <div class="flex items-center justify-between mb-4 pb-3 border-b border-slate-100">
                        <div class="flex items-center gap-2">
                            <div class="w-8 h-8 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-sm">
                                <i class="fas fa-chart-pie"></i>
                            </div>
                            <h3 class="font-display font-bold text-base text-ocean-950">Proporsi Suara</h3>
                        </div>
                        <span class="text-[10px] font-bold uppercase tracking-wider px-2.5 py-0.5 rounded-full bg-slate-100 text-slate-600 border border-slate-200">Lingkaran</span>
                    </div>
                    <div class="relative w-full" style="height:300px">
                        <canvas id="myChartPie"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- ── Quick Links Row ── -->
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 anim-up anim-up-d4">
            @php
                $quickLinks = [
                    ['href' => '/admin/user',           'icon' => 'fas fa-users',      'label' => 'Data Pemilih',    'sub' => 'Kelola DPT'],
                    ['href' => '/admin/osis-candidate', 'icon' => 'fas fa-id-card',    'label' => 'Data Paslon',     'sub' => 'Kelola kandidat'],
                    ['href' => '/admin/admin-list',     'icon' => 'fas fa-user-shield','label' => 'Data Admin',      'sub' => 'Kelola admin'],
                    ['href' => '/admin/web-setting',    'icon' => 'fas fa-sliders-h',  'label' => 'Pengaturan Web',  'sub' => 'Konfigurasi sistem'],
                ];
            @endphp
            @foreach($quickLinks as $ql)
            <a href="{{ $ql['href'] }}"
               class="bezel-card-shell group block text-decoration-none">
                <div class="bezel-card-core p-4 !flex-row items-center gap-3">
                    <div class="w-9 h-9 rounded-xl bg-ocean-50 border border-ocean-100 text-ocean-500 flex items-center justify-center text-sm shrink-0 group-hover:bg-ocean-500 group-hover:text-white transition-colors duration-300">
                        <i class="{{ $ql['icon'] }}"></i>
                    </div>
                    <div class="min-w-0">
                        <div class="text-xs font-bold text-ocean-950 truncate">{{ $ql['label'] }}</div>
                        <div class="text-[10px] text-slate-400 mt-0.5">{{ $ql['sub'] }}</div>
                    </div>
                </div>
            </a>
            @endforeach
        </div>

    </div>
</div>

@endsection

@section('pagescript')
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.socket.io/4.7.5/socket.io.min.js"></script>

<script>
const initialCandidates = {!! \Illuminate\Support\Js::from($osis_candidate) !!};
const candidateName     = {!! \Illuminate\Support\Js::from($candidate_name)  !!};
const candidateTotal    = {!! \Illuminate\Support\Js::from($candidate_total) !!};

const PALETTE = ['#2872A1','#10b981','#f59e0b','#6366f1','#ec4899','#06b6d4'];

let barChart = null, pieChart = null;

const app = new Vue({
    el: '#app',
    data: {
        stats: {
            total_voters:       {{ $user_total }},
            total_voted:        {{ $already_chosen }},
            total_unvoted:      {{ $not_yet_chosen }},
            turnout_percentage: {{ $user_total > 0 ? round(($already_chosen / $user_total) * 100, 1) : 0 }},
            unvoted_percentage: {{ $user_total > 0 ? round(($not_yet_chosen / $user_total) * 100, 1) : 0 }},
        },
        osisCandidate: initialCandidates,
        lastUpdated: '{{ now()->format("H:i:s") }}',
        isSocketConnected: false,
        pollingTimer: null,
    },
    computed: {
        maxVotes() {
            return Math.max(...this.osisCandidate.map(c => c.total || 0), 0);
        }
    },
    methods: {
        isLeader(candidate) {
            return this.maxVotes > 0 && (candidate.total || 0) === this.maxVotes;
        },
        initCharts() {
            const baseFont = { family: "'Plus Jakarta Sans', sans-serif" };

            const ctxBar = document.getElementById('myChart');
            if (ctxBar) {
                barChart = new Chart(ctxBar, {
                    type: 'bar',
                    data: {
                        labels: candidateName,
                        datasets: [{
                            label: 'Perolehan Suara',
                            data: candidateTotal,
                            backgroundColor: PALETTE,
                            borderRadius: 10,
                            maxBarThickness: 54,
                        }]
                    },
                    options: {
                        responsive: true, maintainAspectRatio: false,
                        animation: { duration: 600, easing: 'easeOutQuart' },
                        scales: {
                            y: { beginAtZero: true,
                                 grid: { color: 'rgba(226,232,240,0.5)', drawBorder: false },
                                 ticks: { precision: 0, font: { ...baseFont, size: 11 }, color: '#64748b' }
                            },
                            x: { grid: { display: false },
                                 ticks: { font: { ...baseFont, size: 11, weight: '600' }, color: '#0b1e2c', maxRotation: 0 }
                            }
                        },
                        plugins: {
                            legend: { display: false },
                            tooltip: {
                                backgroundColor: '#0b1e2c', padding: 12, cornerRadius: 12,
                                titleFont: { family: "'Outfit', sans-serif", weight: '700', size: 13 },
                                bodyFont: { ...baseFont, size: 12 },
                                callbacks: { label: ctx => ' ' + ctx.parsed.y.toLocaleString('id-ID') + ' suara' }
                            }
                        }
                    }
                });
            }

            const ctxPie = document.getElementById('myChartPie');
            if (ctxPie) {
                pieChart = new Chart(ctxPie, {
                    type: 'doughnut',
                    data: {
                        labels: candidateName,
                        datasets: [{
                            data: candidateTotal,
                            backgroundColor: PALETTE,
                            borderColor: '#ffffff',
                            borderWidth: 3,
                            hoverOffset: 8,
                        }]
                    },
                    options: {
                        responsive: true, maintainAspectRatio: false, cutout: '70%',
                        animation: { duration: 600, easing: 'easeOutQuart' },
                        plugins: {
                            legend: {
                                position: 'bottom',
                                labels: { ...baseFont, boxWidth: 12, boxHeight: 12, useBorderRadius: true, borderRadius: 4, padding: 16, color: '#0b1e2c',
                                          font: { ...baseFont, size: 11, weight: '600' } }
                            },
                            tooltip: {
                                backgroundColor: '#0b1e2c', padding: 12, cornerRadius: 12,
                                titleFont: { family: "'Outfit', sans-serif", weight: '700', size: 13 },
                                bodyFont: { ...baseFont, size: 12 },
                                callbacks: { label: ctx => ' ' + ctx.parsed.toFixed(1) + '%' }
                            }
                        }
                    }
                });
            }
        },
        applyVoteData(data) {
            if (!data || !data.summary || !data.candidates) return;

            this.stats = data.summary;
            this.lastUpdated = data.last_updated;

            data.candidates.forEach(cand => {
                const found = this.osisCandidate.find(c => c.id == cand.id);
                if (found) {
                    found.total    = cand.votes;
                    found.percentase = cand.percentage_dpt;
                }
            });

            const labels = data.candidates.map(c => '#' + c.sequence_number + ' ' + c.name);
            const votes  = data.candidates.map(c => c.votes);

            if (barChart) { barChart.data.labels = labels; barChart.data.datasets[0].data = votes; barChart.update(); }
            if (pieChart)  { pieChart.data.labels = labels; pieChart.data.datasets[0].data = votes; pieChart.update(); }
        },
        pollLiveVotes() {
            axios.get('/api/live-votes')
                .then(res => { if (res.data && res.data.status === 'success') this.applyVoteData(res.data); })
                .catch(() => {});
        },
        initRealtimeSocket() {
            if (typeof io !== 'undefined') {
                try {
                    const socket = io(window.location.protocol + '//' + window.location.hostname + ':3001', {
                        transports: ['websocket', 'polling'], timeout: 4000,
                        reconnectionAttempts: Infinity, reconnectionDelay: 2000,
                    });
                    socket.on('connect',       () => { this.isSocketConnected = true; if (this.pollingTimer) { clearInterval(this.pollingTimer); this.pollingTimer = null; } });
                    socket.on('voteUpdate',    data => { this.applyVoteData(data); });
                    socket.on('connect_error', () => { this.isSocketConnected = false; this.startFallbackPolling(); });
                    socket.on('disconnect',    () => { this.isSocketConnected = false; this.startFallbackPolling(); });
                } catch { this.startFallbackPolling(); }
            } else {
                this.startFallbackPolling();
            }
        },
        startFallbackPolling() {
            if (!this.pollingTimer) this.pollingTimer = setInterval(this.pollLiveVotes, 5000);
        },
        confirmResetAllVotes() {
            Swal.fire({
                title: 'Reset Seluruh Suara?',
                text: 'Semua suara masuk akan dikosongkan dan status pemilih dikembalikan ke Belum Memilih. Gunakan hanya untuk keperluan uji coba.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#e11d48',
                cancelButtonColor: '#64748b',
                confirmButtonText: '<i class="fas fa-trash-alt mr-1"></i> Kosongkan Suara',
                cancelButtonText: 'Batal',
                reverseButtons: true,
                customClass: {
                    popup: 'rounded-3xl shadow-bezel font-sans',
                    confirmButton: 'rounded-full px-5 py-2.5 font-display font-bold text-sm',
                    cancelButton:  'rounded-full px-5 py-2.5 font-display font-bold text-sm'
                }
            }).then(result => {
                if (!result.isConfirmed) return;
                Swal.fire({ title: 'Mereset…', text: 'Mohon tunggu', allowOutsideClick: false, didOpen: () => Swal.showLoading() });
                axios.post('/admin/reset-all-votes', {}, { headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' } })
                    .then(res => {
                        Swal.fire({ icon: 'success', title: 'Berhasil di-Reset', text: res.data.message || 'Seluruh suara berhasil dikosongkan.',
                                    timer: 2000, showConfirmButton: false, customClass: { popup: 'rounded-3xl shadow-bezel' } })
                            .then(() => window.location.reload());
                    })
                    .catch(err => {
                        Swal.fire({ icon: 'error', title: 'Gagal', text: err.response?.data?.message || 'Terjadi kesalahan sistem.',
                                    customClass: { popup: 'rounded-3xl shadow-bezel' } });
                    });
            });
        }
    },
    mounted() {
        this.initCharts();
        this.pollLiveVotes();
        this.initRealtimeSocket();
    }
});
</script>
@endsection
