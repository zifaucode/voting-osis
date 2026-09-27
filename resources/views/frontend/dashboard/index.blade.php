<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $web_setting?->web_title ?? 'VOTING OSIS' }} - E-Voting Modern</title>
    <meta name="description" content="Aplikasi E-Voting Pemilihan Ketua dan Wakil Ketua OSIS">

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
    <link rel="stylesheet" href="{{ asset('user-page/assets/vendor/aos/aos.css') }}">

    <!-- Glassmorphism Main Stylesheet -->
    <link rel="stylesheet" href="{{ asset('css/glassmorphism.css') }}">

    <style>
        [v-cloak] {
            display: none;
        }
        .hidden-timer {
            display: none;
        }
    </style>
</head>

<body>
    <div id="app" class="ambient-wrapper" v-cloak>
        <!-- Glass Navbar -->
        <header class="glass-navbar">
            <div class="container d-flex align-items-center justify-content-between">
                <a href="/" class="brand-title">
                    <img src="{{ $web_setting?->logo_image ? asset('gambar/logo/' . $web_setting->logo_image) : asset('gambar/logo-osis.png') }}" alt="Logo OSIS">
                    <span>{{ $web_setting?->web_title ?? 'PEMILOS DIGITAL' }}</span>
                </a>

                <div class="d-flex align-items-center gap-2 gap-md-3">
                    <div class="glass-navbar-badge d-none d-lg-inline-flex" v-if="status == 'buka'">
                        <span class="status-dot"></span> Pemilihan Berlangsung
                    </div>
                    <div class="glass-navbar-badge d-none d-lg-inline-flex" v-else-if="status == 'tutup'" style="background: rgba(239, 68, 68, 0.1); border-color: rgba(239, 68, 68, 0.3); color: #dc2626;">
                        <span class="status-dot" style="background-color: #ef4444; box-shadow: 0 0 10px #ef4444;"></span> Pemilihan Berakhir
                    </div>

                    @if(isset($current_user) && $current_user)
                    <!-- Voter Profile Pill -->
                    <div class="d-none d-md-flex align-items-center py-1 px-3 rounded-pill" style="background: rgba(255, 255, 255, 0.8); border: 1px solid rgba(255, 255, 255, 0.95); box-shadow: 0 4px 15px rgba(0,0,0,0.03); gap: 0.6rem;">
                        <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 32px; height: 32px; background: var(--primary-emerald); color: white; font-weight: 700; font-size: 0.85rem;">
                            <i class="bi bi-person-fill"></i>
                        </div>
                        <div class="text-left" style="line-height: 1.2;">
                            <span class="d-block font-weight-bold" style="font-size: 0.85rem; color: var(--text-main);">{{ $current_user->name }}</span>
                            <span class="d-block text-muted" style="font-size: 0.72rem;">Username: {{ $current_user->nisn }} &bull; {{ $current_user->class }}</span>
                        </div>
                    </div>
                    @endif

                    <button type="button" class="glass-btn-secondary" data-toggle="modal" data-target="#visiMisiModal" title="Lihat Rekap Visi Misi">
                        <i class="bi bi-card-text"></i> <span class="d-none d-sm-inline">Visi & Misi</span>
                    </button>

                    @if(isset($current_user) && $current_user)
                    <a href="{{ route('logout.user') }}" class="glass-btn-secondary" style="border-color: rgba(239, 68, 68, 0.35); color: #dc2626;" title="Keluar dari Bilik Suara">
                        <i class="bi bi-box-arrow-right"></i> <span class="d-none d-sm-inline">Keluar</span>
                    </a>
                    @else
                    <a href="/admin" class="glass-btn-secondary" title="Portal Masuk Admin">
                        <i class="bi bi-shield-lock"></i> <span class="d-none d-sm-inline">Admin</span>
                    </a>
                    @endif
                </div>
            </div>
        </header>

        <!-- Main Content -->
        <main class="py-4">
            <!-- Hero Header Section -->
            <div class="container">
                <div class="glass-section-header" data-aos="fade-up">
                    <div class="school-badge">
                        <i class="bi bi-award-fill"></i>
                        <span>{{ $web_setting?->school_name ?? 'SMK / SMA INDONESIA' }}</span>
                        <span>•</span>
                        <span>PERIODE {{ $web_setting?->year_period ?? '2026' }} / {{ $year_plus ?? '2027' }}</span>
                    </div>
                    <h1>{{ $web_setting?->web_title ?? 'PEMILIHAN KETUA & WAKIL OSIS' }}</h1>
                    <p>Gunakan hak suara Anda secara bijak, jujur, dan adil untuk menentukan pemimpin generasi masa depan sekolah kita.</p>
                </div>

                <!-- Voter Status & Instructions Banner -->
                @if(isset($current_user) && $current_user)
                <div class="row justify-content-center mb-4" data-aos="fade-up">
                    <div class="col-lg-10">
                        @if($current_user->vote_status == 1)
                        <div class="p-3 px-4 d-flex align-items-center justify-content-between flex-wrap gap-3" style="background: rgba(16, 185, 129, 0.12); border: 1.5px solid rgba(16, 185, 129, 0.35); border-radius: 20px; backdrop-filter: blur(12px);">
                            <div class="d-flex align-items-center gap-3">
                                <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0" style="width: 46px; height: 46px; background: var(--primary-emerald); color: white; font-size: 1.4rem; box-shadow: 0 4px 12px rgba(5, 150, 105, 0.3);">
                                    <i class="bi bi-check2-all"></i>
                                </div>
                                <div>
                                    <div class="font-weight-bold" style="color: var(--primary-dark); font-size: 1.1rem;">
                                        Terima Kasih, {{ $current_user->name }}!
                                    </div>
                                    <small class="text-muted d-block" style="font-size: 0.85rem;">
                                        Suara Anda telah berhasil tercatat dengan aman pada sistem e-voting. Satu pemilih hanya dapat memilih 1 kali.
                                    </small>
                                </div>
                            </div>
                            <span class="badge badge-success px-3 py-2 font-weight-bold" style="border-radius: 999px; font-size: 0.85rem; background: var(--primary-emerald);">
                                <i class="bi bi-shield-check mr-1"></i> Hak Suara Sudah Digunakan
                            </span>
                        </div>
                        @else
                        <div class="p-3 px-4 d-flex align-items-center justify-content-between flex-wrap gap-3" style="background: rgba(255, 255, 255, 0.85); backdrop-filter: blur(12px); border: 1.5px solid rgba(255, 255, 255, 0.95); border-radius: 20px; box-shadow: 0 10px 30px rgba(15, 23, 42, 0.05);">
                            <div class="d-flex align-items-center gap-3">
                                <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0" style="width: 46px; height: 46px; background: var(--primary-emerald); color: white; font-size: 1.35rem; box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);">
                                    <i class="bi bi-person-badge-fill"></i>
                                </div>
                                <div>
                                    <div class="font-weight-bold" style="color: var(--text-main); font-size: 1.05rem;">
                                        Bilik Suara Aktif: <strong>{{ $current_user->name }}</strong> ({{ $current_user->class }})
                                    </div>
                                    <small class="text-muted d-block" style="font-size: 0.85rem;">
                                        Username: <strong>{{ $current_user->nisn }}</strong> &bull; Silakan pelajari visi, misi, dan program kerja, kemudian klik tombol <strong>"PILIH PASANGAN INI"</strong>.
                                    </small>
                                </div>
                            </div>
                            <span class="badge badge-warning px-3 py-2 text-dark font-weight-bold" style="border-radius: 999px; font-size: 0.82rem; background: #fef08a; border: 1px solid #fde047;">
                                <i class="bi bi-fingerprint mr-1"></i> Belum Memilih
                            </span>
                        </div>
                        @endif
                    </div>
                </div>
                @endif

                <!-- Hidden containers for backward script compatibility -->
                <div class="hidden-timer">
                    <span id="demo"></span>
                    <span id="getCountdown"></span>
                </div>



                <!-- Standby or Closed State Notices -->
                <div class="text-center my-4" v-if="status == 'tutup'" data-aos="fade-up">
                    <div class="glass-card d-inline-block p-4 mx-auto" style="max-width: 500px;">
                        <div class="mb-3">
                            <i class="bi bi-check-circle-fill text-success" style="font-size: 3rem;"></i>
                        </div>
                        <h3 class="font-weight-bold">Pemilihan Telah Ditutup</h3>
                        <p class="text-muted mb-0">Terima kasih atas partisipasi seluruh siswa dan siswi dalam pemungutan suara ini.</p>
                    </div>
                </div>

                <div class="text-center my-4" v-if="status == ''" data-aos="fade-up">
                    <div class="glass-card d-inline-block p-4 mx-auto" style="max-width: 550px;">
                        <div class="mb-2">
                            <i class="bi bi-clock-history text-primary" style="font-size: 2.5rem;"></i>
                        </div>
                        <h4 class="font-weight-bold mb-2">Menunggu Waktu Pemilihan</h4>
                        <p class="text-muted mb-3">Sesi pemungutan suara belum dimulai. Perhitungan mundur menuju pembukaan:</p>
                        <div class="glass-countdown-container mb-0">
                            <div class="glass-countdown-item">
                                <div class="glass-countdown-num">@{{ countdown.days }}</div>
                                <div class="glass-countdown-lbl">Hari</div>
                            </div>
                            <div class="glass-countdown-item">
                                <div class="glass-countdown-num">@{{ countdown.hours }}</div>
                                <div class="glass-countdown-lbl">Jam</div>
                            </div>
                            <div class="glass-countdown-item">
                                <div class="glass-countdown-num">@{{ countdown.minutes }}</div>
                                <div class="glass-countdown-lbl">Menit</div>
                            </div>
                            <div class="glass-countdown-item">
                                <div class="glass-countdown-num">@{{ countdown.seconds }}</div>
                                <div class="glass-countdown-lbl">Detik</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Candidate Selection Grid -->
                <section class="mt-4 mb-5" v-if="status == 'buka'">
                    <div class="row g-4 justify-content-center">
                        <div class="col-lg-4 col-md-6 mb-4 d-flex align-items-stretch" data-aos="zoom-in" :data-aos-delay="index * 100" v-for="(candidate, index) in osisCandidate" :key="candidate.id">
                            <div class="glass-card w-100 d-flex flex-column">
                                <div class="glass-card-header">
                                    <div class="glass-badge-seq">
                                        <i class="bi bi-star-fill" style="font-size: 0.85rem;"></i>
                                        <span>#@{{ candidate.sequence_number ?? (index + 1) }}</span>
                                    </div>
                                    <span class="glass-badge-class">
                                        Paslon OSIS
                                    </span>
                                </div>

                                <div class="candidate-photo-frame">
                                    <img :src="candidate.image ? '/file/image/' + candidate.image : '/gambar/logo-osis.png'" :alt="candidate.name" onerror="this.src='/gambar/logo-osis.png'">
                                </div>

                                <div v-if="candidate.slogan" class="my-2 px-3 py-1 text-center font-italic small" style="background: rgba(16, 185, 129, 0.08); border-radius: 8px; color: var(--primary-emerald); font-weight: 600; line-height: 1.4;">
                                    “@{{ candidate.slogan }}”
                                </div>

                                <!-- Calon Ketua & Wakil Ketua -->
                                <div class="candidate-info text-left p-2 mb-3" style="background: rgba(255, 255, 255, 0.45); border-radius: 12px; border: 1px solid rgba(0,0,0,0.04);">
                                    <div class="d-flex align-items-center justify-content-between mb-2">
                                        <div>
                                            <small class="text-muted d-block" style="font-size: 0.72rem; text-transform: uppercase; font-weight: 700; color: #059669 !important;">Calon Ketua</small>
                                            <h4 class="mb-0 font-weight-bold" style="font-size: 1.05rem; color: var(--text-main);">@{{ candidate.name }}</h4>
                                        </div>
                                        <span class="candidate-class-badge">@{{ candidate.class }}</span>
                                    </div>

                                    <div v-if="candidate.vice_name" class="d-flex align-items-center justify-content-between pt-2 border-top" style="border-color: rgba(0,0,0,0.06) !important;">
                                        <div>
                                            <small class="text-muted d-block" style="font-size: 0.72rem; text-transform: uppercase; font-weight: 700; color: #0284c7 !important;">Calon Wakil Ketua</small>
                                            <h4 class="mb-0 font-weight-bold" style="font-size: 1.05rem; color: var(--text-main);">@{{ candidate.vice_name }}</h4>
                                        </div>
                                        <span class="candidate-class-badge">@{{ candidate.vice_class }}</span>
                                    </div>
                                </div>

                                <!-- VISI KANDIDAT -->
                                <div class="candidate-card-section">
                                    <div class="candidate-card-label" style="color: #d97706;">
                                        <i class="bi bi-lightbulb-fill"></i> Visi
                                    </div>
                                    <div class="candidate-card-scrollbox">
                                        @{{ candidate.visi || 'Belum diisi oleh kandidat.' }}
                                    </div>
                                </div>

                                <!-- MISI KANDIDAT -->
                                <div class="candidate-card-section">
                                    <div class="candidate-card-label" style="color: #4f46e5;">
                                        <i class="bi bi-bullseye"></i> Misi
                                    </div>
                                    <div class="candidate-card-scrollbox">
                                        @{{ candidate.misi || 'Belum diisi oleh kandidat.' }}
                                    </div>
                                </div>

                                <!-- PROGRAM KERJA UNGGULAN -->
                                <div class="candidate-card-section mb-3">
                                    <div class="candidate-card-label" style="color: #059669;">
                                        <i class="bi bi-briefcase-fill"></i> Program Kerja Unggulan
                                    </div>
                                    <div class="candidate-card-scrollbox">
                                        @{{ candidate.work_program || 'Belum diisi oleh kandidat.' }}
                                    </div>
                                </div>

                                <!-- Tombol Aksi Memilih -->
                                <div class="mt-auto pt-2">
                                    @if(isset($current_user) && $current_user->vote_status == 1)
                                    <button type="button" class="glass-btn-secondary w-100 text-muted" disabled style="cursor: not-allowed; opacity: 0.75; font-size: 0.95rem;">
                                        <i class="bi bi-check2-all text-success"></i> Suara Anda Telah Tercatat
                                    </button>
                                    @else
                                    <button type="button" @click="confirmVote(candidate)" class="glass-btn-primary w-100" :disabled="loading">
                                        <i class="bi bi-box-arrow-in-down"></i> PILIH PASANGAN INI
                                    </button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </main>

        <!-- Modal Visi Misi & Program Kerja Overview -->
        <div class="modal fade glass-modal" id="visiMisiModal" tabindex="-1" role="dialog" aria-labelledby="visiMisiModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" id="visiMisiModalLabel">
                            <i class="bi bi-journal-text text-success mr-2"></i> Rekap Visi, Misi & Program Kerja Paslon
                        </h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="font-size: 1.75rem; border: none; background: transparent;">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-lg-6 mb-4" v-for="candidate in osisCandidate" :key="candidate.id">
                                <div class="visi-misi-card h-100">
                                    <div class="d-flex align-items-center justify-content-between mb-2 pb-2 border-bottom">
                                        <div class="d-flex align-items-center gap-2">
                                            <span class="glass-badge-seq" style="font-size: 0.95rem; padding: 0.25rem 0.75rem;">
                                                #@{{ candidate.sequence_number }}
                                            </span>
                                            <div>
                                                <h5 class="font-weight-bold mb-0 text-dark">
                                                    @{{ candidate.name }} <span class="text-muted font-weight-normal small">(@{{ candidate.class }})</span>
                                                </h5>
                                                <div v-if="candidate.vice_name" class="text-muted small">
                                                    Wakil: @{{ candidate.vice_name }} (@{{ candidate.vice_class }})
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div v-if="candidate.slogan" class="font-italic small text-muted mb-3 px-2 py-1 bg-light rounded">
                                        <i class="bi bi-chat-quote-fill text-warning mr-1"></i> “@{{ candidate.slogan }}”
                                    </div>

                                    <div class="mb-3">
                                        <h4><i class="bi bi-lightbulb-fill text-warning"></i> VISI</h4>
                                        <div class="p-3 bg-white rounded-lg shadow-sm" style="border-left: 4px solid var(--primary-light); white-space: pre-line;">
                                            @{{ candidate.visi || 'Belum diisi oleh kandidat.' }}
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <h4><i class="bi bi-bullseye text-primary"></i> MISI</h4>
                                        <div class="p-3 bg-white rounded-lg shadow-sm" style="border-left: 4px solid var(--accent-indigo); white-space: pre-line;">
                                            @{{ candidate.misi || 'Belum diisi oleh kandidat.' }}
                                        </div>
                                    </div>

                                    <div v-if="candidate.work_program">
                                        <h4><i class="bi bi-briefcase-fill text-success"></i> PROGRAM KERJA UNGGULAN</h4>
                                        <div class="p-3 bg-white rounded-lg shadow-sm" style="border-left: 4px solid var(--primary-emerald); white-space: pre-line;">
                                            @{{ candidate.work_program }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="glass-btn-secondary" data-dismiss="modal">
                            Tutup
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <footer class="py-4 text-center text-muted" style="position: relative; z-index: 2; border-top: 1px solid var(--glass-border-subtle); background: rgba(255, 255, 255, 0.5); backdrop-filter: blur(10px);">
            <div class="container">
                <small class="d-block font-weight-bold text-dark mb-1">
                    {{ $web_setting?->web_title ?? 'PEMILOS' }} &copy; {{ date('Y') }} • Hak Suara Digital Sekolah
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

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/vue@2/dist/vue.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="{{ asset('user-page/assets/vendor/aos/aos.js') }}"></script>

    <script>
        AOS.init({
            duration: 800,
            once: true
        });

        const osisCandidate = <?php echo Illuminate\Support\Js::from($osis_candidate) ?>;
        const currentUser = <?php echo isset($current_user) ? Illuminate\Support\Js::from($current_user) : 'null' ?>;

        let app = new Vue({
            el: '#app',
            data: {
                osisCandidate,
                currentUser,
                status: '',
                loading: false,
                countdown: {
                    days: '00',
                    hours: '00',
                    minutes: '00',
                    seconds: '00'
                }
            },
            methods: {
                confirmVote: function(candidate) {
                    if (this.loading) return;

                    if (this.currentUser && this.currentUser.vote_status == 1) {
                        Swal.fire({
                            title: 'Perhatian',
                            text: 'Anda sudah pernah menggunakan hak suara. Setiap pemilih hanya dapat memilih 1 kali.',
                            icon: 'warning',
                            confirmButtonColor: '#059669'
                        });
                        return;
                    }

                    let candidateTitle = candidate.name;
                    if (candidate.vice_name) {
                        candidateTitle += ' & ' + candidate.vice_name;
                    }

                    let sloganHtml = candidate.slogan 
                        ? `<div class="font-italic small my-2" style="color: #059669; font-weight: 600;">“${candidate.slogan}”</div>` 
                        : '';

                    let vm = this;

                    Swal.fire({
                        title: 'Konfirmasi Pilihan Suara',
                        html: `
                            <div class="text-left p-3 rounded" style="background: rgba(248, 250, 252, 0.9); border: 1.5px solid #e2e8f0;">
                                <div class="text-center mb-2">
                                    <span class="badge badge-success px-3 py-1 font-weight-bold" style="font-size: 0.95rem; border-radius: 999px;">
                                        Paslon #${candidate.sequence_number}
                                    </span>
                                    <h5 class="mt-2 mb-1 font-weight-bold text-dark" style="font-size: 1.15rem;">${candidateTitle}</h5>
                                    ${sloganHtml}
                                </div>
                                <div class="p-2 bg-white rounded border mb-2 text-dark small">
                                    <div class="d-flex justify-content-between align-items-center py-1">
                                        <span><strong>Ketua:</strong> ${candidate.name}</span>
                                        <span class="candidate-class-badge">${candidate.class}</span>
                                    </div>
                                    ${candidate.vice_name ? `
                                    <div class="d-flex justify-content-between align-items-center py-1 border-top">
                                        <span><strong>Wakil:</strong> ${candidate.vice_name}</span>
                                        <span class="candidate-class-badge">${candidate.vice_class}</span>
                                    </div>` : ''}
                                </div>
                                <div class="text-muted small text-center mt-2">
                                    <i class="bi bi-shield-exclamation text-warning mr-1"></i>
                                    Pilihan suara Anda bersifat final dan <strong>tidak dapat diubah</strong> setelah dikonfirmasi.
                                </div>
                            </div>
                        `,
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonColor: '#059669',
                        cancelButtonColor: '#94a3b8',
                        confirmButtonText: '<i class="bi bi-check2-circle mr-1"></i> Ya, Pilih Sekarang',
                        cancelButtonText: '<i class="bi bi-x-circle mr-1"></i> Batal',
                        reverseButtons: true,
                        showLoaderOnConfirm: true,
                        allowOutsideClick: () => !Swal.isLoading(),
                        preConfirm: () => {
                            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

                            return axios.post('/user-choose', {
                                candidate_id: candidate.id
                            }, {
                                headers: {
                                    'X-CSRF-TOKEN': csrfToken || '',
                                    'Accept': 'application/json'
                                }
                            })
                            .then(response => {
                                return response.data;
                            })
                            .catch(error => {
                                if (error.response && error.response.status === 419) {
                                    Swal.showValidationMessage('Sesi login telah kedaluwarsa. Silakan refresh halaman.');
                                    return;
                                }
                                let msg = error.response && error.response.data && error.response.data.message 
                                    ? error.response.data.message 
                                    : 'Terjadi kesalahan saat memproses suara Anda.';
                                Swal.showValidationMessage(msg);
                            });
                        }
                    }).then((result) => {
                        if (result.isConfirmed && result.value) {
                            Swal.fire({
                                title: 'Berhasil Memilih!',
                                text: 'Terima kasih, suara Anda telah berhasil disimpan oleh sistem. Sesi Anda telah selesai dan akan otomatis keluar dari bilik suara.',
                                icon: 'success',
                                confirmButtonColor: '#059669',
                                confirmButtonText: 'Selesai & Keluar',
                                allowOutsideClick: false,
                            }).then(() => {
                                window.location.href = '/user/login';
                            });
                        }
                    });
                }
            }
        });
    </script>

    @if($web_setting)
    <script>
        (function() {
            var startDateStr = "{!! $web_setting->start_date !!}T{!! $web_setting->start_time !!}+07:00";
            var endDateStr = "{!! $web_setting->start_date !!}T{!! $web_setting->end_time !!}+07:00";
            var countDownStart = new Date(startDateStr).getTime();
            var countDownEnd = new Date(endDateStr).getTime();

            var timerInterval = setInterval(function() {
                var now = new Date().getTime();
                var distToStart = countDownStart - now;
                var distToEnd = countDownEnd - now;

                function formatNum(n) {
                    return n < 10 ? '0' + n : '' + n;
                }

                // Sesi belum dimulai
                if (distToStart > 0) {
                    app.status = '';
                    var d = Math.floor(distToStart / (1000 * 60 * 60 * 24));
                    var h = Math.floor((distToStart % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                    var m = Math.floor((distToStart % (1000 * 60 * 60)) / (1000 * 60));
                    var s = Math.floor((distToStart % (1000 * 60)) / 1000);
                    app.countdown = { days: formatNum(d), hours: formatNum(h), minutes: formatNum(m), seconds: formatNum(s) };
                }
                // Sesi sedang berlangsung
                else if (distToStart <= 0 && distToEnd > 0) {
                    app.status = 'buka';
                    var d = Math.floor(distToEnd / (1000 * 60 * 60 * 24));
                    var h = Math.floor((distToEnd % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                    var m = Math.floor((distToEnd % (1000 * 60 * 60)) / (1000 * 60));
                    var s = Math.floor((distToEnd % (1000 * 60)) / 1000);
                    app.countdown = { days: formatNum(d), hours: formatNum(h), minutes: formatNum(m), seconds: formatNum(s) };
                }
                // Sesi selesai
                else {
                    clearInterval(timerInterval);
                    app.status = 'tutup';
                    app.countdown = { days: '00', hours: '00', minutes: '00', seconds: '00' };
                }
            }, 1000);
        })();
    </script>
    @endif
</body>

</html>