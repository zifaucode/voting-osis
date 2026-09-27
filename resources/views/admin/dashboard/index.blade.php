@extends('layouts-admin.app')
@section('title')
Dashboard Admin | Pemilos
@endsection

@section('head')
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Outfit:wght@500;600;700;800;900&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

<style>
    /* Tabular figures for data integrity */
    .tabular-nums {
        font-variant-numeric: tabular-nums;
    }

    /* Live sync pulsing micro-dot */
    .live-dot {
        width: 8px;
        height: 8px;
        background-color: #10b981;
        border-radius: 50%;
        display: inline-block;
        box-shadow: 0 0 10px rgba(16, 185, 129, 0.8);
        animation: pulseLive 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
    }

    @keyframes pulseLive {
        0%, 100% {
            opacity: 1;
            transform: scale(1);
        }
        50% {
            opacity: 0.4;
            transform: scale(1.15);
        }
    }
</style>
@endsection

@section('content')

<div id="app" v-cloak class="min-h-screen px-4 sm:px-6 lg:px-8 py-6 sm:py-8">
    <div class="max-w-7xl mx-auto space-y-8">

        <!-- Top Header & Live Broadcast Bar -->
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-5 pb-2">
            <div>
                <div class="flex items-center gap-2 mb-2">
                    <span class="eyebrow-badge">
                        <i class="fas fa-satellite-dish text-ocean-500"></i>
                        <span>KONSOL ADMINISTRATOR</span>
                    </span>
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200/80">
                        <span class="live-dot"></span>
                        <span class="tabular-nums">Live Sync: @{{ lastUpdated }}</span>
                    </span>
                </div>
                <h1 class="font-display font-extrabold text-2xl sm:text-3xl lg:text-4xl text-ocean-950 tracking-tight">
                    Dashboard Pemilihan OSIS
                </h1>
                <p class="text-sm text-slate-500 mt-1">
                    Pemantauan realtime hasil pemungutan suara, statistik DPT, dan perolehan kandidat.
                </p>
            </div>

            <!-- Header Quick Actions -->
            <div class="flex flex-wrap items-center gap-3">
                <!-- Reset Votes Button -->
                <button @click="confirmResetAllVotes" 
                        type="button" 
                        class="inline-flex items-center gap-2 px-4 py-2.5 rounded-full bg-rose-50 hover:bg-rose-100 text-rose-700 font-display font-bold text-xs sm:text-sm border border-rose-200/80 active:scale-[0.98] transition-all duration-200 ease-spring shadow-sm">
                    <i class="fas fa-trash-restore text-rose-500"></i>
                    <span>Reset Suara Uji Coba</span>
                </button>

                <!-- Open Live Count Button (Button-in-Button) -->
                <a href="/live-count" target="_blank" class="btn-island-primary">
                    <span>Buka Live Count</span>
                    <div class="btn-island-icon">
                        <i class="fas fa-external-link-alt text-xs"></i>
                    </div>
                </a>
            </div>
        </div>

        <!-- 3 Primary Metric Cards (Double-Bezel Architecture) -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            
            <!-- Metric 1: Total DPT -->
            <div class="bezel-card-shell">
                <div class="bezel-card-core justify-between">
                    <div>
                        <div class="flex items-center justify-between">
                            <span class="text-[11px] font-bold tracking-wider uppercase text-slate-400">Total DPT</span>
                            <div class="w-10 h-10 rounded-2xl bg-ocean-50 text-ocean-600 flex items-center justify-center border border-ocean-100">
                                <i class="fas fa-users text-base"></i>
                            </div>
                        </div>
                        <div class="mt-4">
                            <div class="font-display font-extrabold text-3xl sm:text-4xl text-ocean-950 tabular-nums">
                                @{{ stats.total_voters }}
                            </div>
                            <span class="text-xs text-slate-500 mt-1 block">Pemilih terdaftar dalam DPT</span>
                        </div>
                    </div>
                    <div class="mt-6 pt-4 border-t border-slate-100 flex items-center justify-between">
                        <a href="/admin/user" class="inline-flex items-center gap-1.5 text-xs font-bold text-ocean-600 hover:text-ocean-700 transition-colors">
                            <span>Kelola Data Pemilih</span>
                            <i class="fas fa-arrow-right text-[10px]"></i>
                        </a>
                        <span class="text-[11px] text-slate-400">Aktif</span>
                    </div>
                </div>
            </div>

            <!-- Metric 2: Suara Masuk -->
            <div class="bezel-card-shell">
                <div class="bezel-card-core justify-between">
                    <div>
                        <div class="flex items-center justify-between">
                            <span class="text-[11px] font-bold tracking-wider uppercase text-slate-400">Suara Masuk</span>
                            <div class="w-10 h-10 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center border border-emerald-100">
                                <i class="fas fa-vote-yea text-base"></i>
                            </div>
                        </div>
                        <div class="mt-4 flex items-baseline gap-3">
                            <div class="font-display font-extrabold text-3xl sm:text-4xl text-emerald-600 tabular-nums">
                                @{{ stats.total_voted }}
                            </div>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-emerald-100 text-emerald-800 tabular-nums">
                                @{{ stats.turnout_percentage }}% Partisipasi
                            </span>
                        </div>
                        <span class="text-xs text-slate-500 mt-1 block">Suara sah telah berhasil diverifikasi</span>
                    </div>
                    <div class="mt-6 pt-4 border-t border-slate-100">
                        <!-- Mini Participation Track -->
                        <div class="w-full bg-slate-100 rounded-full h-2 overflow-hidden">
                            <div class="bg-emerald-500 h-2 rounded-full transition-all duration-700 ease-spring"
                                 :style="'width: ' + stats.turnout_percentage + '%'"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Metric 3: Belum Memilih -->
            <div class="bezel-card-shell">
                <div class="bezel-card-core justify-between">
                    <div>
                        <div class="flex items-center justify-between">
                            <span class="text-[11px] font-bold tracking-wider uppercase text-slate-400">Belum Memilih</span>
                            <div class="w-10 h-10 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center border border-amber-100">
                                <i class="fas fa-user-clock text-base"></i>
                            </div>
                        </div>
                        <div class="mt-4 flex items-baseline gap-3">
                            <div class="font-display font-extrabold text-3xl sm:text-4xl text-amber-600 tabular-nums">
                                @{{ stats.total_unvoted }}
                            </div>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-amber-100 text-amber-800 tabular-nums">
                                @{{ stats.unvoted_percentage }}% Sisa
                            </span>
                        </div>
                        <span class="text-xs text-slate-500 mt-1 block">Siswa belum menyalurkan hak suara</span>
                    </div>
                    <div class="mt-6 pt-4 border-t border-slate-100">
                        <div class="w-full bg-slate-100 rounded-full h-2 overflow-hidden">
                            <div class="bg-amber-400 h-2 rounded-full transition-all duration-700 ease-spring"
                                 :style="'width: ' + stats.unvoted_percentage + '%'"></div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <!-- Candidate Cards Section -->
        <div class="bezel-card-shell">
            <div class="bezel-card-core">
                <!-- Section Header -->
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-6 mb-6 border-b border-slate-100">
                    <div>
                        <div class="flex items-center gap-2">
                            <div class="w-2.5 h-2.5 rounded-full bg-ocean-500"></div>
                            <h2 class="font-display font-extrabold text-xl text-ocean-950">
                                Perolehan Suara Pasangan Calon
                            </h2>
                        </div>
                        <p class="text-xs text-slate-500 mt-1">
                            Distribusi suara langsung per kandidat Ketua & Wakil Ketua OSIS terpilih.
                        </p>
                    </div>
                    <a href="/admin/osis-candidate" class="btn-island-secondary self-start sm:self-auto">
                        <i class="fas fa-id-card text-ocean-500"></i>
                        <span>Kelola Paslon</span>
                    </a>
                </div>

                <!-- Candidates Bento Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <div v-for="candidate in osisCandidate" :key="candidate.id" 
                         class="group relative rounded-3xl bg-slate-50/70 border border-slate-200/80 p-5 hover:bg-white hover:border-ocean-300 hover:shadow-bezel-hover transition-all duration-300 ease-spring flex flex-col justify-between">
                        
                        <div>
                            <!-- Header Pills: Sequence Number & Class -->
                            <div class="flex items-center justify-between mb-4">
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-display font-extrabold bg-ocean-500 text-white shadow-sm">
                                    <span>No. Urut</span>
                                    <span class="tabular-nums text-sm">@{{ candidate.sequence_number }}</span>
                                </span>
                                <span class="px-2.5 py-1 rounded-full text-xs font-semibold bg-white border border-slate-200 text-slate-600">
                                    @{{ candidate.class }}
                                </span>
                            </div>

                            <!-- Candidate Photo Squircle -->
                            <div class="relative aspect-[4/3] rounded-2xl overflow-hidden bg-slate-100 border border-slate-200/90 shadow-inner mb-4 group-hover:scale-[1.01] transition-transform duration-300">
                                <img :src="'/file/image/' + candidate.image" 
                                     onerror="this.src='/gambar/logo-osis.png'" 
                                     class="w-full h-full object-cover object-top" 
                                     :alt="candidate.name">
                                <div class="absolute inset-0 bg-gradient-to-t from-black/40 via-transparent to-transparent opacity-60"></div>
                            </div>

                            <!-- Names & Info -->
                            <div class="text-center px-1">
                                <h3 class="font-display font-extrabold text-lg text-ocean-950 tracking-tight">
                                    @{{ candidate.name }}
                                </h3>
                                
                                <div v-if="candidate.vice_name" class="text-xs text-slate-600 mt-1 flex items-center justify-center gap-1.5 flex-wrap">
                                    <span>Wakil:</span>
                                    <span class="font-bold text-ocean-900">@{{ candidate.vice_name }}</span>
                                    <span class="px-1.5 py-0.5 rounded bg-slate-200/70 text-[10px] text-slate-700 font-medium">@{{ candidate.vice_class }}</span>
                                </div>
                                <p v-else class="text-xs text-slate-400 mt-1">Calon Tunggal Ketua OSIS</p>

                                <!-- Slogan Callout -->
                                <div v-if="candidate.slogan" class="mt-3 p-2.5 rounded-xl bg-white border border-slate-100 text-xs italic text-slate-500 shadow-sm text-center">
                                    “@{{ candidate.slogan }}”
                                </div>
                            </div>
                        </div>

                        <!-- Vote Progress Footer -->
                        <div class="mt-6 pt-4 border-t border-slate-200/80">
                            <div class="flex items-baseline justify-between mb-2">
                                <span class="text-xs font-semibold text-slate-500">Suara Terkumpul:</span>
                                <div class="flex items-baseline gap-1">
                                    <span class="font-display font-extrabold text-xl text-ocean-600 tabular-nums">@{{ candidate.total }}</span>
                                    <span class="text-xs font-semibold text-slate-400">suara</span>
                                </div>
                            </div>

                            <!-- Progress Track -->
                            <div class="w-full bg-slate-200/80 rounded-full h-2.5 overflow-hidden">
                                <div class="bg-gradient-to-r from-ocean-500 to-ocean-600 h-2.5 rounded-full transition-all duration-500 ease-spring"
                                     :style="'width: ' + (candidate.percentase || 0) + '%'"></div>
                            </div>

                            <div class="flex items-center justify-between text-[11px] font-semibold text-slate-500 mt-2">
                                <span>Persentase DPT:</span>
                                <span class="text-ocean-950 font-bold tabular-nums">@{{ Number(candidate.percentase || 0).toFixed(1) }}%</span>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>

        <!-- Charts Bento Section (7-col / 5-col) -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
            
            <!-- Bar Chart (7 Cols) -->
            <div class="lg:col-span-7 bezel-card-shell">
                <div class="bezel-card-core">
                    <div class="flex items-center justify-between mb-4 pb-3 border-b border-slate-100">
                        <div class="flex items-center gap-2">
                            <div class="w-2.5 h-2.5 rounded-full bg-ocean-500"></div>
                            <h3 class="font-display font-bold text-base text-ocean-950">
                                Grafik Perolehan Suara
                            </h3>
                        </div>
                        <span class="text-[11px] font-semibold text-slate-400">Diagram Batang</span>
                    </div>
                    <div class="relative w-full" style="height: 300px;">
                        <canvas id="myChart"></canvas>
                    </div>
                </div>
            </div>

            <!-- Doughnut Chart (5 Cols) -->
            <div class="lg:col-span-5 bezel-card-shell">
                <div class="bezel-card-core">
                    <div class="flex items-center justify-between mb-4 pb-3 border-b border-slate-100">
                        <div class="flex items-center gap-2">
                            <div class="w-2.5 h-2.5 rounded-full bg-emerald-500"></div>
                            <h3 class="font-display font-bold text-base text-ocean-950">
                                Proporsi Suara Masuk
                            </h3>
                        </div>
                        <span class="text-[11px] font-semibold text-slate-400">Diagram Lingkaran</span>
                    </div>
                    <div class="relative w-full" style="height: 300px;">
                        <canvas id="myChartPie"></canvas>
                    </div>
                </div>
            </div>

        </div>

    </div>
</div>

@endsection

@section('pagescript')
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.socket.io/4.7.5/socket.io.min.js"></script>

<script>
    const initialCandidates = <?php echo Illuminate\Support\Js::from($osis_candidate) ?>;
    const candidateName = <?php echo Illuminate\Support\Js::from($candidate_name) ?>;
    const candidateTotal = <?php echo Illuminate\Support\Js::from($candidate_total) ?>;

    // Harmonious High-End Palette (Ocean Blue, Cloudy Sky, Emerald, Coral, Indigo)
    const paletteColors = [
        '#2872A1', // Primary Ocean Blue
        '#10b981', // Emerald
        '#f59e0b', // Amber
        '#6366f1', // Indigo
        '#ec4899', // Pink
        '#06b6d4', // Cyan
    ];

    let barChart = null;
    let pieChart = null;

    let app = new Vue({
        el: '#app',
        data: {
            stats: {
                total_voters: {{ $user_total }},
                total_voted: {{ $already_chosen }},
                total_unvoted: {{ $not_yet_chosen }},
                turnout_percentage: {{ $user_total > 0 ? round(($already_chosen / $user_total) * 100, 1) : 0 }},
                unvoted_percentage: {{ $user_total > 0 ? round(($not_yet_chosen / $user_total) * 100, 1) : 0 }},
            },
            osisCandidate: initialCandidates,
            lastUpdated: '{{ now()->format("H:i:s") }}',
            isSocketConnected: false,
            pollingTimer: null,
        },
        mounted() {
            this.initCharts();
            this.initRealtimeSocket();
            this.pollLiveVotes(); // Initial fetch
        },
        methods: {
            initCharts() {
                const ctx = document.getElementById('myChart');
                if (ctx) {
                    barChart = new Chart(ctx, {
                        type: 'bar',
                        data: {
                            labels: candidateName,
                            datasets: [{
                                label: 'Perolehan Suara',
                                data: candidateTotal,
                                backgroundColor: paletteColors,
                                borderRadius: 12,
                                maxBarThickness: 54,
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            animation: { duration: 600 },
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    grid: {
                                        color: 'rgba(226, 232, 240, 0.6)',
                                    },
                                    ticks: { 
                                        precision: 0,
                                        font: {
                                            family: "'Plus Jakarta Sans', sans-serif",
                                            size: 11
                                        }
                                    }
                                },
                                x: {
                                    grid: {
                                        display: false
                                    },
                                    ticks: {
                                        font: {
                                            family: "'Plus Jakarta Sans', sans-serif",
                                            size: 11,
                                            weight: '600'
                                        }
                                    }
                                }
                            },
                            plugins: {
                                legend: { display: false },
                                tooltip: {
                                    padding: 12,
                                    cornerRadius: 12,
                                    titleFont: { family: "'Outfit', sans-serif", size: 13 },
                                    bodyFont: { family: "'Plus Jakarta Sans', sans-serif", size: 12 }
                                }
                            }
                        }
                    });
                }

                const pieCh = document.getElementById('myChartPie');
                if (pieCh) {
                    pieChart = new Chart(pieCh, {
                        type: 'doughnut',
                        data: {
                            labels: candidateName,
                            datasets: [{
                                label: 'Suara',
                                data: candidateTotal,
                                backgroundColor: paletteColors,
                                borderWidth: 3,
                                borderColor: '#ffffff',
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            cutout: '72%',
                            animation: { duration: 600 },
                            plugins: {
                                legend: {
                                    position: 'bottom',
                                    labels: { 
                                        boxWidth: 12, 
                                        padding: 14,
                                        font: {
                                            family: "'Plus Jakarta Sans', sans-serif",
                                            size: 11,
                                            weight: '500'
                                        }
                                    }
                                },
                                tooltip: {
                                    padding: 12,
                                    cornerRadius: 12,
                                    titleFont: { family: "'Outfit', sans-serif", size: 13 },
                                    bodyFont: { family: "'Plus Jakarta Sans', sans-serif", size: 12 }
                                }
                            }
                        }
                    });
                }
            },
            initRealtimeSocket() {
                if (typeof io !== 'undefined') {
                    try {
                        const socketUrl = `${window.location.protocol}//${window.location.hostname}:3001`;
                        const socket = io(socketUrl, {
                            transports: ['websocket', 'polling'],
                            timeout: 4000,
                            reconnectionAttempts: Infinity,
                            reconnectionDelay: 2000,
                        });

                        socket.on('connect', () => {
                            console.log('⚡ Admin Dashboard connected to WebSocket!');
                            this.isSocketConnected = true;
                            if (this.pollingTimer) {
                                clearInterval(this.pollingTimer);
                                this.pollingTimer = null;
                            }
                        });

                        socket.on('voteUpdate', (data) => {
                            console.log('📡 Admin Dashboard received instant voteUpdate:', data);
                            this.applyVoteData(data);
                        });

                        socket.on('connect_error', () => {
                            this.isSocketConnected = false;
                            this.startFallbackPolling();
                        });

                        socket.on('disconnect', () => {
                            this.isSocketConnected = false;
                            this.startFallbackPolling();
                        });
                    } catch (e) {
                        console.warn('Socket error on admin:', e);
                        this.startFallbackPolling();
                    }
                } else {
                    this.startFallbackPolling();
                }
            },
            startFallbackPolling() {
                if (!this.pollingTimer) {
                    this.pollingTimer = setInterval(this.pollLiveVotes, 5000);
                }
            },
            applyVoteData(data) {
                if (!data || !data.summary || !data.candidates) return;

                this.stats = data.summary;
                this.lastUpdated = data.last_updated;

                // Update candidate total & percentages
                data.candidates.forEach(cand => {
                    const found = this.osisCandidate.find(c => c.id == cand.id);
                    if (found) {
                        found.total = cand.votes;
                        found.percentase = cand.percentage_dpt;
                    }
                });

                // Update charts
                const labels = data.candidates.map(c => `#${c.sequence_number} ${c.name}`);
                const votes = data.candidates.map(c => c.votes);

                if (barChart) {
                    barChart.data.labels = labels;
                    barChart.data.datasets[0].data = votes;
                    barChart.update();
                }
                if (pieChart) {
                    pieChart.data.labels = labels;
                    pieChart.data.datasets[0].data = votes;
                    pieChart.update();
                }
            },
            pollLiveVotes() {
                axios.get('/api/live-votes')
                    .then(res => {
                        if (res.data && res.data.status === 'success') {
                            this.applyVoteData(res.data);
                        }
                    })
                    .catch(err => {
                        console.warn('Polling live-votes error:', err);
                    });
            },
            confirmResetAllVotes() {
                Swal.fire({
                    title: 'Reset Seluruh Suara Pemilihan?',
                    text: 'Semua suara masuk akan dikosongkan menjadi 0 dan status seluruh siswa akan dikembalikan ke Belum Memilih. Fitur ini membersihkan data uji coba sebelum pemungutan suara resmi dimulai.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#e11d48',
                    cancelButtonColor: '#64748b',
                    confirmButtonText: '<i class="fas fa-trash-alt mr-1"></i> Ya, Kosongkan Suara!',
                    cancelButtonText: 'Batal',
                    reverseButtons: true,
                    customClass: {
                        popup: 'rounded-3xl shadow-bezel font-sans',
                        confirmButton: 'rounded-full px-5 py-2.5 font-display font-bold text-sm',
                        cancelButton: 'rounded-full px-5 py-2.5 font-display font-bold text-sm'
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        Swal.fire({
                            title: 'Mereset Suara...',
                            text: 'Mohon tunggu sebentar',
                            allowOutsideClick: false,
                            didOpen: () => {
                                Swal.showLoading();
                            }
                        });

                        axios.post('/admin/reset-all-votes', {}, {
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            }
                        })
                        .then(res => {
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil di-Reset!',
                                text: res.data.message || 'Seluruh suara berhasil dikosongkan.',
                                timer: 2000,
                                showConfirmButton: false,
                                customClass: {
                                    popup: 'rounded-3xl shadow-bezel'
                                }
                            }).then(() => {
                                window.location.reload();
                            });
                        })
                        .catch(err => {
                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal',
                                text: err.response?.data?.message || 'Terjadi kesalahan sistem saat mereset suara.',
                                customClass: {
                                    popup: 'rounded-3xl shadow-bezel'
                                }
                            });
                        });
                    }
                });
            }
        }
    });
</script>
@endsection