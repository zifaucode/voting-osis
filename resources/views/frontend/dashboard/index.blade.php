<!DOCTYPE html>
<html lang="id" class="h-full">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $web_setting?->web_title ?? 'VOTING OSIS' }} - Bilik Suara Digital</title>
    <meta name="description" content="Aplikasi E-Voting Modern Pemilihan Ketua dan Wakil Ketua OSIS">

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
        [v-cloak] {
            display: none;
        }
        .custom-scrollbar::-webkit-scrollbar {
            width: 4px;
        }
        .custom-scrollbar::-webkit-scrollbar-track {
            background: transparent;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #cbdde9;
            border-radius: 9999px;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: #2872A1;
        }
    </style>
</head>

<body class="min-h-[100dvh] mesh-gradient-bg flex flex-col justify-between text-ocean-950 font-sans antialiased selection:bg-ocean-500 selection:text-white">
    <div id="app" v-cloak class="min-h-full flex flex-col justify-between">
        <!-- Floating Island Detached Navbar -->
        <header class="floating-island-nav">
            <!-- Brand -->
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
                        Bilik Suara Siswa
                    </span>
                </div>
            </a>

            <!-- Right Controls -->
            <div class="flex items-center gap-2 sm:gap-3">
                <!-- Status Chip -->
                <div class="hidden lg:inline-flex items-center gap-2 px-3 py-1 rounded-full bg-ocean-50 border border-ocean-200/80 text-ocean-700 text-xs font-bold" v-if="status == 'buka'">
                    <span class="w-2 h-2 rounded-full bg-ocean-500 animate-pulse shadow-[0_0_6px_rgba(40,114,161,0.8)]"></span>
                    <span>Pemilihan Berlangsung</span>
                </div>
                <div class="hidden lg:inline-flex items-center gap-2 px-3 py-1 rounded-full bg-rose-50 border border-rose-200/80 text-rose-700 text-xs font-bold" v-else-if="status == 'tutup'">
                    <span class="w-2 h-2 rounded-full bg-rose-500"></span>
                    <span>Pemilihan Ditutup</span>
                </div>

                @if(isset($current_user) && $current_user)
                <!-- Voter Profile Chip -->
                <div class="hidden md:flex items-center gap-2.5 px-3 py-1.5 rounded-full bg-white/90 border border-white/95 shadow-sm">
                    <div class="w-7 h-7 rounded-full bg-gradient-to-tr from-ocean-600 to-ocean-500 text-white flex items-center justify-center font-bold text-xs shadow-sm">
                        <i class="bi bi-person-fill"></i>
                    </div>
                    <div class="text-left leading-tight pr-1">
                        <span class="block font-bold text-xs text-ocean-950">{{ $current_user->name }}</span>
                        <span class="block text-[10px] text-cloudy-500 font-medium">{{ $current_user->class }}</span>
                    </div>
                </div>

                <a href="{{ route('logout.user') }}" class="btn-island-secondary hover:text-rose-600 hover:border-rose-200" title="Keluar dari Bilik Suara">
                    <i class="bi bi-box-arrow-right text-sm"></i>
                    <span class="hidden sm:inline">Keluar</span>
                </a>
                @else
                <a href="/admin/login" class="btn-island-secondary" title="Portal Masuk Admin">
                    <i class="bi bi-shield-lock text-sm"></i>
                    <span class="hidden sm:inline">Admin</span>
                </a>
                @endif
            </div>
        </header>

        <!-- Main Hero Section -->
        <main class="flex-grow pt-28 pb-12 sm:pt-36 sm:pb-20 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto w-full">
            <!-- Hero Header -->
            <div class="text-center max-w-3xl mx-auto mb-10 sm:mb-14">
                <div class="eyebrow-badge mb-3">
                    <i class="bi bi-award-fill text-ocean-500"></i>
                    <span>{{ $web_setting?->school_name ?? 'SMK / SMA INDONESIA' }}</span>
                    <span class="text-cloudy-300">•</span>
                    <span>PERIODE {{ $web_setting?->year_period ?? '2026' }} / {{ $year_plus ?? '2027' }}</span>
                </div>

                <h1 class="text-3xl sm:text-5xl lg:text-6xl font-display font-extrabold text-ocean-950 tracking-tight text-balance leading-tight">
                    {{ $web_setting?->web_title ?? 'PEMILIHAN KETUA & WAKIL OSIS' }}
                </h1>
                
                <p class="mt-3 text-sm sm:text-base text-cloudy-600 text-balance max-w-2xl mx-auto leading-relaxed">
                    Tentukan pemimpin organisasi sekolah masa depan. Pelajari visi, misi, dan program kerja kandidat, lalu berikan suara Anda secara langsung dan rahasia.
                </p>
            </div>

            <!-- Voter Status Notice -->
            @if(isset($current_user) && $current_user)
            <div class="max-w-4xl mx-auto mb-10">
                @if($current_user->vote_status == 1)
                <div class="bezel-card-shell">
                    <div class="bezel-card-core p-4 sm:p-5 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                        <div class="flex items-center gap-3.5">
                            <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-ocean-500 to-ocean-600 text-white flex items-center justify-center text-xl shrink-0 shadow-glow">
                                <i class="bi bi-check2-all"></i>
                            </div>
                            <div>
                                <h3 class="font-display font-bold text-base sm:text-lg text-ocean-950">
                                    Terima Kasih, {{ $current_user->name }}!
                                </h3>
                                <p class="text-xs sm:text-sm text-cloudy-600 leading-snug">
                                    Suara Anda telah berhasil tersimpan dengan aman pada bilik suara elektronik. Satu pemilih hanya dapat memilih 1 kali.
                                </p>
                            </div>
                        </div>
                        <span class="self-start sm:self-center shrink-0 px-4 py-1.5 rounded-full bg-ocean-50 border border-ocean-200 text-ocean-800 text-xs font-bold flex items-center gap-1.5 shadow-sm">
                            <i class="bi bi-shield-check text-ocean-600"></i> Hak Suara Digunakan
                        </span>
                    </div>
                </div>
                @else
                <div class="bezel-card-shell">
                    <div class="bezel-card-core p-4 sm:p-5 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                        <div class="flex items-center gap-3.5">
                            <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-white to-ocean-50 border border-ocean-200/80 text-ocean-600 flex items-center justify-center text-xl shrink-0 shadow-sm">
                                <i class="bi bi-person-badge-fill"></i>
                            </div>
                            <div>
                                <h3 class="font-display font-bold text-base sm:text-lg text-ocean-950">
                                    Bilik Suara Aktif: {{ $current_user->name }} <span class="text-xs text-cloudy-500 font-normal">({{ $current_user->class }})</span>
                                </h3>
                                <p class="text-xs sm:text-sm text-cloudy-600 leading-snug">
                                    Username: <strong>{{ $current_user->nisn }}</strong> &bull; Tekan tombol <strong>"PILIH PASANGAN INI"</strong> pada kandidat pilihan Anda.
                                </p>
                            </div>
                        </div>
                        <span class="self-start sm:self-center shrink-0 px-4 py-1.5 rounded-full bg-amber-50 border border-amber-200 text-amber-800 text-xs font-bold flex items-center gap-1.5">
                            <i class="bi bi-fingerprint text-amber-600"></i> Belum Memilih
                        </span>
                    </div>
                </div>
                @endif
            </div>
            @endif

            <!-- Standby Countdown State -->
            <div v-if="status == ''" class="max-w-2xl mx-auto my-8">
                <div class="bezel-card-shell text-center">
                    <div class="bezel-card-core p-6 sm:p-10">
                        <div class="w-16 h-16 mx-auto mb-4 rounded-3xl bg-ocean-50 border border-ocean-200 text-ocean-600 flex items-center justify-center text-2xl shadow-sm">
                            <i class="bi bi-clock-history"></i>
                        </div>
                        <h2 class="text-xl sm:text-2xl font-display font-bold text-ocean-950">
                            Menunggu Waktu Pemilihan
                        </h2>
                        <p class="mt-1 text-xs sm:text-sm text-cloudy-600 max-w-md mx-auto">
                            Sesi pemungutan suara belum dibuka. Perhitungan mundur menuju pembukaan bilik suara:
                        </p>

                        <!-- Countdown Tiles -->
                        <div class="grid grid-cols-4 gap-2 sm:gap-4 max-w-md mx-auto mt-6">
                            <div class="p-3 sm:p-4 rounded-2xl bg-white border border-slate-100 shadow-sm">
                                <div class="font-display font-extrabold text-2xl sm:text-3xl text-ocean-600 tabular-nums">@{{ countdown.days }}</div>
                                <div class="text-[10px] font-bold uppercase tracking-wider text-cloudy-500 mt-1">Hari</div>
                            </div>
                            <div class="p-3 sm:p-4 rounded-2xl bg-white border border-slate-100 shadow-sm">
                                <div class="font-display font-extrabold text-2xl sm:text-3xl text-ocean-600 tabular-nums">@{{ countdown.hours }}</div>
                                <div class="text-[10px] font-bold uppercase tracking-wider text-cloudy-500 mt-1">Jam</div>
                            </div>
                            <div class="p-3 sm:p-4 rounded-2xl bg-white border border-slate-100 shadow-sm">
                                <div class="font-display font-extrabold text-2xl sm:text-3xl text-ocean-600 tabular-nums">@{{ countdown.minutes }}</div>
                                <div class="text-[10px] font-bold uppercase tracking-wider text-cloudy-500 mt-1">Menit</div>
                            </div>
                            <div class="p-3 sm:p-4 rounded-2xl bg-white border border-slate-100 shadow-sm">
                                <div class="font-display font-extrabold text-2xl sm:text-3xl text-ocean-600 tabular-nums">@{{ countdown.seconds }}</div>
                                <div class="text-[10px] font-bold uppercase tracking-wider text-cloudy-500 mt-1">Detik</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Closed State Notice -->
            <div v-if="status == 'tutup'" class="max-w-xl mx-auto my-8">
                <div class="bezel-card-shell text-center">
                    <div class="bezel-card-core p-8">
                        <div class="w-16 h-16 mx-auto mb-4 rounded-3xl bg-rose-50 border border-rose-200 text-rose-600 flex items-center justify-center text-3xl">
                            <i class="bi bi-check-circle-fill"></i>
                        </div>
                        <h2 class="text-xl sm:text-2xl font-display font-bold text-ocean-950">
                            Pemilihan Telah Ditutup
                        </h2>
                        <p class="mt-2 text-sm text-cloudy-600 leading-relaxed">
                            Terima kasih atas partisipasi seluruh siswa dan siswi dalam pemungutan suara ini. Hasil rekapitulasi dapat dipantau di halaman Live Count.
                        </p>
                        <a href="/live-count" class="btn-island-primary mt-6 inline-flex">
                            <span>Buka Live Count</span>
                            <div class="btn-island-icon">
                                <i class="bi bi-broadcast text-white"></i>
                            </div>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Candidate Selection Grid (Active State) -->
            <section v-if="status == 'buka'" class="mt-4">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 sm:gap-8 items-stretch">
                    <div v-for="(candidate, index) in osisCandidate" :key="candidate.id" class="bezel-card-shell flex flex-col h-full">
                        <div class="bezel-card-core flex flex-col justify-between flex-grow">
                            <!-- Card Top Bar -->
                            <div>
                                <div class="flex items-center justify-between mb-3">
                                    <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-gradient-to-r from-ocean-500 to-ocean-600 text-white font-display font-extrabold text-sm shadow-sm tabular-nums">
                                        <i class="bi bi-star-fill text-[11px]"></i>
                                        <span>#@{{ candidate.sequence_number ?? (index + 1) }}</span>
                                    </div>
                                    <span class="text-[11px] font-bold uppercase tracking-wider text-cloudy-600 bg-cloudy-100 px-2.5 py-0.5 rounded-full">
                                        Paslon OSIS
                                    </span>
                                </div>

                                <!-- Photo Frame -->
                                <div class="relative w-full h-56 sm:h-64 my-3 rounded-2xl bg-gradient-to-br from-slate-50 to-ocean-50/40 border border-slate-100/80 p-3 flex items-center justify-center overflow-hidden shadow-[inset_0_2px_4px_rgba(15,23,42,0.03)] group-hover:border-ocean-200 transition-colors">
                                    <img :src="candidate.image ? '/file/image/' + candidate.image : '/gambar/logo-osis.png'" 
                                         :alt="candidate.name" 
                                         onerror="this.src='/gambar/logo-osis.png'"
                                         class="max-w-full max-h-full object-contain filter drop-shadow transition-transform duration-500 ease-spring group-hover:scale-105">
                                </div>

                                <!-- Slogan -->
                                <div v-if="candidate.slogan" class="my-2.5 px-3 py-1.5 rounded-xl bg-ocean-50/70 border border-ocean-100 text-center font-medium italic text-xs text-ocean-800 leading-snug">
                                    “@{{ candidate.slogan }}”
                                </div>

                                <!-- Candidate Names Bento Box -->
                                <div class="p-3.5 my-3 rounded-2xl bg-slate-50/80 border border-slate-100 space-y-2.5">
                                    <div class="flex items-start justify-between gap-2">
                                        <div>
                                            <span class="block text-[10px] font-bold uppercase tracking-wider text-ocean-600">Calon Ketua</span>
                                            <h4 class="font-display font-bold text-sm sm:text-base text-ocean-950 leading-tight">@{{ candidate.name }}</h4>
                                        </div>
                                        <span class="text-[11px] font-bold text-ocean-900 bg-white border border-slate-200/80 px-2 py-0.5 rounded-md shadow-xs shrink-0">@{{ candidate.class }}</span>
                                    </div>

                                    <div v-if="candidate.vice_name" class="pt-2 border-t border-slate-200/70 flex items-start justify-between gap-2">
                                        <div>
                                            <span class="block text-[10px] font-bold uppercase tracking-wider text-cloudy-600">Calon Wakil</span>
                                            <h4 class="font-display font-bold text-sm sm:text-base text-ocean-950 leading-tight">@{{ candidate.vice_name }}</h4>
                                        </div>
                                        <span class="text-[11px] font-bold text-ocean-900 bg-white border border-slate-200/80 px-2 py-0.5 rounded-md shadow-xs shrink-0">@{{ candidate.vice_class }}</span>
                                    </div>
                                </div>

                                <!-- Visi Section -->
                                <div class="mb-2.5 text-left">
                                    <span class="text-[10px] font-bold uppercase tracking-wider text-amber-600 flex items-center gap-1 mb-1">
                                        <i class="bi bi-lightbulb-fill"></i> Visi
                                    </span>
                                    <div class="p-2.5 rounded-xl bg-white border border-slate-100 text-xs text-cloudy-700 leading-relaxed max-h-24 overflow-y-auto custom-scrollbar whitespace-pre-line shadow-[inset_0_1px_2px_rgba(15,23,42,0.02)]">
                                        @{{ candidate.visi || 'Belum diisi oleh kandidat.' }}
                                    </div>
                                </div>

                                <!-- Misi Section -->
                                <div class="mb-2.5 text-left">
                                    <span class="text-[10px] font-bold uppercase tracking-wider text-ocean-600 flex items-center gap-1 mb-1">
                                        <i class="bi bi-bullseye"></i> Misi
                                    </span>
                                    <div class="p-2.5 rounded-xl bg-white border border-slate-100 text-xs text-cloudy-700 leading-relaxed max-h-24 overflow-y-auto custom-scrollbar whitespace-pre-line shadow-[inset_0_1px_2px_rgba(15,23,42,0.02)]">
                                        @{{ candidate.misi || 'Belum diisi oleh kandidat.' }}
                                    </div>
                                </div>

                                <!-- Program Kerja Section -->
                                <div v-if="candidate.work_program" class="mb-3 text-left">
                                    <span class="text-[10px] font-bold uppercase tracking-wider text-teal-600 flex items-center gap-1 mb-1">
                                        <i class="bi bi-briefcase-fill"></i> Program Kerja
                                    </span>
                                    <div class="p-2.5 rounded-xl bg-white border border-slate-100 text-xs text-cloudy-700 leading-relaxed max-h-24 overflow-y-auto custom-scrollbar whitespace-pre-line shadow-[inset_0_1px_2px_rgba(15,23,42,0.02)]">
                                        @{{ candidate.work_program }}
                                    </div>
                                </div>
                            </div>

                            <!-- Bottom Action Button -->
                            <div class="pt-2 mt-auto">
                                @if(isset($current_user) && $current_user->vote_status == 1)
                                <button type="button" class="w-full py-3 px-4 rounded-full bg-slate-100 text-cloudy-400 font-display font-bold text-xs sm:text-sm cursor-not-allowed flex items-center justify-center gap-2 border border-slate-200" disabled>
                                    <i class="bi bi-check2-all text-ocean-500"></i> Suara Anda Telah Tercatat
                                </button>
                                @else
                                <button type="button" @click="confirmVote(candidate)" class="btn-island-primary w-full" :disabled="loading">
                                    <span>PILIH PASANGAN INI</span>
                                    <div class="btn-island-icon">
                                        <i class="bi bi-box-arrow-in-down text-white text-sm"></i>
                                    </div>
                                </button>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </section>
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
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/vue@2/dist/vue.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
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
                            confirmButtonColor: '#2872A1'
                        });
                        return;
                    }

                    let candidateTitle = candidate.name;
                    if (candidate.vice_name) {
                        candidateTitle += ' & ' + candidate.vice_name;
                    }

                    let sloganHtml = candidate.slogan 
                        ? `<div class="italic text-xs my-2 text-ocean-600 font-medium">“${candidate.slogan}”</div>` 
                        : '';

                    let vm = this;

                    Swal.fire({
                        title: 'Konfirmasi Pilihan Suara',
                        html: `
                            <div class="text-left p-4 rounded-2xl bg-slate-50 border border-slate-200">
                                <div class="text-center mb-3">
                                    <span class="inline-block px-3 py-1 font-bold text-white text-xs rounded-full bg-ocean-500 shadow-sm">
                                        Paslon #${candidate.sequence_number}
                                    </span>
                                    <h4 class="mt-2 font-display font-bold text-base text-ocean-950">${candidateTitle}</h4>
                                    ${sloganHtml}
                                </div>
                                <div class="p-3 bg-white rounded-xl border border-slate-100 text-xs space-y-1.5 text-ocean-900">
                                    <div class="flex justify-between items-center">
                                        <span><strong>Ketua:</strong> ${candidate.name}</span>
                                        <span class="bg-slate-100 px-2 py-0.5 rounded font-bold">${candidate.class}</span>
                                    </div>
                                    ${candidate.vice_name ? `
                                    <div class="flex justify-between items-center pt-1.5 border-t border-slate-100">
                                        <span><strong>Wakil:</strong> ${candidate.vice_name}</span>
                                        <span class="bg-slate-100 px-2 py-0.5 rounded font-bold">${candidate.vice_class}</span>
                                    </div>` : ''}
                                </div>
                                <div class="text-cloudy-500 text-[11px] text-center mt-3">
                                    <i class="bi bi-shield-exclamation text-ocean-500 mr-1"></i>
                                    Pilihan suara Anda bersifat final dan <strong>tidak dapat diubah</strong> setelah dikonfirmasi.
                                </div>
                            </div>
                        `,
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonColor: '#2872A1',
                        cancelButtonColor: '#94a3b8',
                        confirmButtonText: 'Ya, Pilih Sekarang',
                        cancelButtonText: 'Batal',
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
                                confirmButtonColor: '#2872A1',
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

                if (distToStart > 0) {
                    app.status = '';
                    var d = Math.floor(distToStart / (1000 * 60 * 60 * 24));
                    var h = Math.floor((distToStart % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                    var m = Math.floor((distToStart % (1000 * 60 * 60)) / (1000 * 60));
                    var s = Math.floor((distToStart % (1000 * 60)) / 1000);
                    app.countdown = { days: formatNum(d), hours: formatNum(h), minutes: formatNum(m), seconds: formatNum(s) };
                }
                else if (distToStart <= 0 && distToEnd > 0) {
                    app.status = 'buka';
                    var d = Math.floor(distToEnd / (1000 * 60 * 60 * 24));
                    var h = Math.floor((distToEnd % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                    var m = Math.floor((distToEnd % (1000 * 60 * 60)) / (1000 * 60));
                    var s = Math.floor((distToEnd % (1000 * 60)) / 1000);
                    app.countdown = { days: formatNum(d), hours: formatNum(h), minutes: formatNum(m), seconds: formatNum(s) };
                }
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