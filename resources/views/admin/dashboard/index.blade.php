@extends('layouts-admin.app')
@section('title')
Dashboard Admin | Pemilos
@endsection

@section('head')
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Outfit:wght@500;600;700;800;900&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('css/glassmorphism.css') }}">

<style>
    body {
        font-family: 'Plus Jakarta Sans', sans-serif;
    }

    h1, h2, h3, h4, h5, h6 {
        font-family: 'Outfit', sans-serif;
    }

    .admin-stat-card {
        background: rgba(255, 255, 255, 0.85);
        backdrop-filter: blur(16px);
        -webkit-backdrop-filter: blur(16px);
        border: 1px solid rgba(255, 255, 255, 0.7);
        border-radius: 22px;
        padding: 1.5rem;
        box-shadow: 0 15px 35px rgba(15, 23, 42, 0.05), inset 0 1px 1px rgba(255, 255, 255, 0.9);
        transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        position: relative;
        overflow: hidden;
    }

    .admin-stat-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 20px 45px rgba(16, 185, 129, 0.12);
    }

    .admin-stat-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
    }

    .stat-blue::before { background: linear-gradient(90deg, #3b82f6, #06b6d4); }
    .stat-green::before { background: linear-gradient(90deg, #10b981, #059669); }
    .stat-amber::before { background: linear-gradient(90deg, #f59e0b, #d97706); }

    .stat-icon-circle {
        width: 54px;
        height: 54px;
        border-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
    }

    .stat-blue .stat-icon-circle { background: rgba(59, 130, 246, 0.12); color: #2563eb; }
    .stat-green .stat-icon-circle { background: rgba(16, 185, 129, 0.12); color: #059669; }
    .stat-amber .stat-icon-circle { background: rgba(245, 158, 11, 0.12); color: #d97706; }

    .stat-big-num {
        font-family: 'Outfit', sans-serif;
        font-size: 2.35rem;
        font-weight: 800;
        line-height: 1.1;
        margin-bottom: 0.2rem;
    }

    .admin-glass-box {
        background: rgba(255, 255, 255, 0.82);
        backdrop-filter: blur(20px);
        -webkit-backdrop-filter: blur(20px);
        border: 1px solid rgba(255, 255, 255, 0.7);
        border-radius: 24px;
        box-shadow: 0 20px 45px rgba(15, 23, 42, 0.05);
        padding: 1.75rem;
        margin-bottom: 1.75rem;
    }

    .candidate-admin-card {
        background: rgba(255, 255, 255, 0.9);
        border: 1px solid rgba(226, 232, 240, 0.8);
        border-radius: 20px;
        padding: 1.25rem;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.03);
        transition: all 0.3s ease;
        height: 100%;
        display: flex;
        flex-direction: column;
    }

    .candidate-admin-card:hover {
        transform: translateY(-6px);
        border-color: rgba(16, 185, 129, 0.4);
        box-shadow: 0 16px 35px rgba(16, 185, 129, 0.12);
    }

    .candidate-admin-img {
        width: 100%;
        height: 220px;
        object-fit: cover;
        border-radius: 14px;
        margin-bottom: 1rem;
        border: 1px solid rgba(0, 0, 0, 0.06);
    }

    .live-dot {
        width: 8px;
        height: 8px;
        background-color: #10b981;
        border-radius: 50%;
        display: inline-block;
        box-shadow: 0 0 8px #10b981;
        animation: pulseLive 1.8s infinite;
    }

    @keyframes pulseLive {
        0% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.7); }
        70% { transform: scale(1.1); box-shadow: 0 0 0 6px rgba(16, 185, 129, 0); }
        100% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(16, 185, 129, 0); }
    }
</style>
@endsection

@section('content')

<div id="app" v-cloak>
    <!-- Header Section -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-3 align-items-center">
                <div class="col-sm-6">
                    <h1 class="m-0 font-weight-bold" style="font-size: 1.85rem; color: #0f172a;">
                        Dashboard Pemilihan OSIS
                    </h1>
                    <div class="d-flex align-items-center gap-2 mt-1">
                        <span class="badge badge-success px-2 py-1 font-weight-bold" style="border-radius: 999px; background: rgba(16, 185, 129, 0.15); color: #065f46; border: 1px solid rgba(16, 185, 129, 0.3);">
                            <span class="live-dot mr-1"></span> Live Auto-Sync: @{{ lastUpdated }}
                        </span>
                        <span class="text-muted small">• Data real-time setiap 3 detik</span>
                    </div>
                </div>
                <div class="col-sm-6 text-sm-right mt-3 mt-sm-0 d-flex flex-wrap justify-content-sm-end align-items-center" style="gap: 0.6rem;">
                    <button @click="confirmResetAllVotes" type="button" class="btn btn-outline-danger px-3 py-2 font-weight-bold shadow-sm" style="border-radius: 12px; font-size: 0.9rem;">
                        <i class="fas fa-trash-restore mr-1"></i> Reset Suara Uji Coba
                    </button>
                    <a href="/live-count" target="_blank" class="btn btn-success px-3 py-2 font-weight-bold shadow-sm" style="border-radius: 12px; font-size: 0.9rem;">
                        <i class="fas fa-broadcast-tower mr-1"></i> Buka Live Count
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content Section -->
    <section class="content">
        <div class="container-fluid">

            <!-- 3 Stat Metrics Cards -->
            <div class="row mb-4">
                <div class="col-lg-4 col-md-6 mb-3">
                    <div class="admin-stat-card stat-blue">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <span class="text-muted font-weight-bold small text-uppercase letter-spacing-1">Total DPT</span>
                                <div class="stat-big-num text-dark">@{{ stats.total_voters }}</div>
                                <span class="small text-muted">Pemilih Terdaftar</span>
                            </div>
                            <div class="stat-icon-circle">
                                <i class="fas fa-users"></i>
                            </div>
                        </div>
                        <div class="mt-3 pt-2 border-top">
                            <a href="/admin/user" class="small font-weight-bold text-primary text-decoration-none">
                                Kelola Data Pemilih &rarr;
                            </a>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6 mb-3">
                    <div class="admin-stat-card stat-green">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <span class="text-muted font-weight-bold small text-uppercase letter-spacing-1">Suara Masuk</span>
                                <div class="stat-big-num text-success">@{{ stats.total_voted }}</div>
                                <span class="badge badge-success px-2 py-1" style="border-radius: 999px;">
                                    @{{ stats.turnout_percentage }}% Partisipasi
                                </span>
                            </div>
                            <div class="stat-icon-circle">
                                <i class="fas fa-vote-yea"></i>
                            </div>
                        </div>
                        <div class="mt-3 pt-2 border-top">
                            <span class="small text-muted">Suara sah telah berhasil direkam</span>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6 mb-3">
                    <div class="admin-stat-card stat-amber">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <span class="text-muted font-weight-bold small text-uppercase letter-spacing-1">Belum Memilih</span>
                                <div class="stat-big-num text-warning">@{{ stats.total_unvoted }}</div>
                                <span class="badge badge-warning px-2 py-1 text-white" style="border-radius: 999px;">
                                    @{{ stats.unvoted_percentage }}% Sisa
                                </span>
                            </div>
                            <div class="stat-icon-circle">
                                <i class="fas fa-user-clock"></i>
                            </div>
                        </div>
                        <div class="mt-3 pt-2 border-top">
                            <span class="small text-muted">Pemilih yang belum menggunakan hak suara</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Candidate Cards Section -->
            <div class="admin-glass-box">
                <div class="d-flex align-items-center justify-content-between mb-4 pb-2 border-bottom">
                    <div>
                        <h4 class="font-weight-bold mb-1 text-dark" style="font-size: 1.35rem;">
                            <i class="fas fa-user-tie text-success mr-2"></i> Perolehan Suara Pasangan Calon
                        </h4>
                        <span class="text-muted small">Status perolehan suara masing-masing kandidat Ketua & Wakil Ketua OSIS</span>
                    </div>
                    <a href="/admin/osis-candidate" class="btn btn-sm btn-outline-secondary" style="border-radius: 8px;">
                        Kelola Paslon
                    </a>
                </div>

                <div class="row">
                    <div class="col-lg-4 col-md-6 mb-4" v-for="candidate in osisCandidate" :key="candidate.id">
                        <div class="candidate-admin-card">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="badge badge-success px-3 py-1 font-weight-bold" style="border-radius: 999px; font-size: 0.85rem;">
                                    No. Urut @{{ candidate.sequence_number }}
                                </span>
                                <span class="badge badge-light px-2 py-1 text-muted">@{{ candidate.class }}</span>
                            </div>

                            <img :src="'/file/image/' + candidate.image" 
                                 onerror="this.src='/gambar/logo-osis.png'" 
                                 class="candidate-admin-img" 
                                 :alt="candidate.name">

                            <h5 class="font-weight-bold text-center mb-1 text-dark">@{{ candidate.name }}</h5>
                            <div v-if="candidate.vice_name" class="text-center small text-muted mb-1">
                                Wakil: <strong>@{{ candidate.vice_name }}</strong> <span class="badge badge-light border">@{{ candidate.vice_class }}</span>
                            </div>
                            <div v-if="candidate.slogan" class="text-center font-italic small text-muted mb-2 px-2 py-1 rounded bg-light">
                                “@{{ candidate.slogan }}”
                            </div>
                            <p v-else class="text-muted text-center small mb-3">Paslon Ketua & Wakil Ketua OSIS</p>

                            <div class="mt-auto">
                                <div class="d-flex justify-content-between align-items-center mb-1 font-weight-bold small">
                                    <span class="text-muted">Total Suara Masuk:</span>
                                    <span class="text-success" style="font-size: 1.1rem;">@{{ candidate.total }}</span>
                                </div>

                                <div class="progress" style="height: 10px; border-radius: 999px;">
                                    <div class="progress-bar bg-success" role="progressbar"
                                         :style="'width: ' + candidate.percentase + '%'" 
                                         :aria-valuenow="candidate.percentase" aria-valuemin="0" aria-valuemax="100">
                                    </div>
                                </div>
                                <div class="text-right mt-1">
                                    <span class="small font-weight-bold text-muted">@{{ Number(candidate.percentase).toFixed(1) }}% dari total DPT</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Charts Section -->
            <div class="row">
                <div class="col-lg-7 mb-4">
                    <div class="admin-glass-box h-100 mb-0">
                        <h5 class="font-weight-bold mb-3 text-dark">
                            <i class="fas fa-chart-bar text-primary mr-2"></i> Grafik Batang Perolehan Suara
                        </h5>
                        <div style="height: 280px; position: relative;">
                            <canvas id="myChart"></canvas>
                        </div>
                    </div>
                </div>

                <div class="col-lg-5 mb-4">
                    <div class="admin-glass-box h-100 mb-0">
                        <h5 class="font-weight-bold mb-3 text-dark">
                            <i class="fas fa-chart-pie text-info mr-2"></i> Proporsi Suara Masuk
                        </h5>
                        <div style="height: 280px; position: relative;">
                            <canvas id="myChartPie"></canvas>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </section>
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

    const paletteColors = [
        '#10b981', // Emerald
        '#06b6d4', // Cyan
        '#6366f1', // Indigo
        '#f59e0b', // Amber
        '#ec4899', // Pink
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
                barChart = new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: candidateName,
                        datasets: [{
                            label: 'Perolehan Suara Sah',
                            data: candidateTotal,
                            backgroundColor: paletteColors,
                            borderRadius: 10,
                            maxBarThickness: 50,
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        animation: { duration: 500 },
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: { precision: 0 }
                            }
                        },
                        plugins: {
                            legend: { display: false }
                        }
                    }
                });

                const pieCh = document.getElementById('myChartPie');
                pieChart = new Chart(pieCh, {
                    type: 'doughnut',
                    data: {
                        labels: candidateName,
                        datasets: [{
                            label: 'Suara',
                            data: candidateTotal,
                            backgroundColor: paletteColors,
                            borderWidth: 2,
                            borderColor: '#ffffff',
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        cutout: '65%',
                        animation: { duration: 500 },
                        plugins: {
                            legend: {
                                position: 'bottom',
                                labels: { boxWidth: 12, padding: 12 }
                            }
                        }
                    }
                });
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
                    text: 'Semua suara masuk akan dikosongkan menjadi 0 dan status seluruh siswa akan dikembalikan ke Belum Memilih. Cocok untuk membersihkan data voting uji coba sebelum hari-H.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#e11d48',
                    cancelButtonColor: '#64748b',
                    confirmButtonText: '<i class="fas fa-trash-alt mr-1"></i> Ya, Kosongkan Suara!',
                    cancelButtonText: 'Batal',
                    reverseButtons: true
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
                                showConfirmButton: false
                            }).then(() => {
                                window.location.reload();
                            });
                        })
                        .catch(err => {
                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal',
                                text: err.response?.data?.message || 'Terjadi kesalahan sistem saat mereset suara.'
                            });
                        });
                    }
                });
            }
        }
    });
</script>
@endsection