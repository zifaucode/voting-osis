<!DOCTYPE html>
<html lang="id" class="h-full">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>Profil Kandidat | {{ $web_setting?->web_title ?? 'PEMILOS' }}</title>
    <meta name="description" content="Lihat profil lengkap, visi, misi, dan program kerja seluruh pasangan calon OSIS.">

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
        [v-cloak] { display: none; }

        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(20px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        @keyframes shimmer {
            0%   { background-position: -200% 0; }
            100% { background-position: 200% 0; }
        }

        .anim-fade-up  { animation: fadeUp 0.55s cubic-bezier(0.16,1,0.3,1) both; }
        .anim-d1       { animation-delay: 0.08s; }
        .anim-d2       { animation-delay: 0.16s; }
        .anim-d3       { animation-delay: 0.25s; }

        /* Candidate number accent stripe */
        .cand-stripe {
            height: 3px;
            border-radius: 9999px;
        }

        ::-webkit-scrollbar        { width: 4px; }
        ::-webkit-scrollbar-track  { background: transparent; }
        ::-webkit-scrollbar-thumb  { background: #cbdde9; border-radius: 9999px; }
        ::-webkit-scrollbar-thumb:hover { background: #2872A1; }
    </style>
</head>

<body class="min-h-[100dvh] mesh-gradient-bg flex flex-col justify-between text-ocean-950 font-sans antialiased selection:bg-ocean-500 selection:text-white">
    <div id="app" v-cloak class="min-h-full flex flex-col justify-between">

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
                        Profil Kandidat
                    </span>
                </div>
            </a>

            <div class="flex items-center gap-2 sm:gap-3">
                <a href="/" class="btn-island-secondary" title="Beranda">
                    <i class="bi bi-house-door text-ocean-500 text-sm"></i>
                    <span class="hidden sm:inline">Beranda</span>
                </a>
                <a href="/user/login" class="btn-island-primary" title="Masuk Bilik Suara">
                    <span class="hidden sm:inline">Bilik Suara</span>
                    <div class="btn-island-icon">
                        <i class="bi bi-box-arrow-in-right text-white text-sm"></i>
                    </div>
                </a>
            </div>
        </header>

        <!-- ═══ Main Content ═══ -->
        <main class="flex-grow pt-28 pb-14 sm:pt-36 sm:pb-20 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto w-full">

            <!-- Page Header -->
            <div class="text-center max-w-3xl mx-auto mb-10 anim-fade-up">
                <div class="eyebrow-badge mb-3">
                    <i class="bi bi-award-fill text-ocean-500"></i>
                    <span>{{ $web_setting?->school_name ?? 'SMK / SMA INDONESIA' }}</span>
                    <span class="text-cloudy-300">•</span>
                    <span>PERIODE {{ $web_setting?->year_period ?? '2026' }} / {{ !empty($year_plus) ? $year_plus : ((int)($web_setting?->year_period ?? 2026) + 1) }}</span>
                </div>

                <h1 class="text-3xl sm:text-4xl lg:text-5xl font-display font-extrabold text-ocean-950 tracking-tight text-balance leading-tight">
                    Pasangan Calon
                    <span class="text-ocean-500">Ketua & Wakil OSIS</span>
                </h1>

                <p class="mt-3 text-sm sm:text-base text-cloudy-600 text-balance max-w-2xl mx-auto leading-relaxed">
                    Kenali visi, misi, dan program kerja masing-masing kandidat sebelum menentukan pilihan Anda.
                </p>

                <div class="mt-5 flex justify-center">
                    <a href="/user/login" class="btn-island-primary">
                        <span>Masuk & Pilih Sekarang</span>
                        <div class="btn-island-icon">
                            <i class="bi bi-fingerprint text-white text-base"></i>
                        </div>
                    </a>
                </div>
            </div>

            <!-- Loading Skeleton -->
            <div v-if="osisCandidate.length === 0" class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
                <div v-for="n in 3" :key="n" class="bezel-card-shell">
                    <div class="bezel-card-core p-5 space-y-4">
                        <div class="flex gap-3">
                            <div class="w-12 h-12 rounded-xl bg-slate-100 animate-pulse shrink-0"></div>
                            <div class="flex-1 space-y-2">
                                <div class="h-3 bg-slate-100 rounded-full animate-pulse w-3/4"></div>
                                <div class="h-3 bg-slate-100 rounded-full animate-pulse w-1/2"></div>
                            </div>
                        </div>
                        <div class="h-48 bg-slate-100 rounded-2xl animate-pulse"></div>
                        <div class="space-y-2">
                            <div class="h-3 bg-slate-100 rounded-full animate-pulse"></div>
                            <div class="h-3 bg-slate-100 rounded-full animate-pulse w-5/6"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Candidate Cards Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6 anim-fade-up anim-d1">
                <div v-for="(candidate, index) in osisCandidate" :key="candidate.id" class="bezel-card-shell flex flex-col h-full">
                    <div class="bezel-card-core flex flex-col justify-between flex-grow relative overflow-visible">

                        <!-- Sequence Badge -->
                        <div>
                            <div class="flex items-center justify-between mb-3">
                                <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-gradient-to-r from-ocean-500 to-ocean-600 text-white font-display font-extrabold text-sm shadow-sm tabular-nums">
                                    <i class="bi bi-star-fill text-[10px]"></i>
                                    <span>#@{{ candidate.sequence_number ?? (index + 1) }}</span>
                                </div>
                                <span class="text-[11px] font-bold uppercase tracking-wider text-cloudy-600 bg-cloudy-100 px-2.5 py-0.5 rounded-full">
                                    Paslon OSIS
                                </span>
                            </div>

                            <!-- Photo Frame -->
                            <div class="relative w-full h-56 sm:h-64 my-3 rounded-2xl bg-gradient-to-br from-slate-50 to-ocean-50/40 border border-slate-100/80 p-3 flex items-center justify-center overflow-hidden shadow-[inset_0_2px_4px_rgba(15,23,42,0.03)]">
                                <img :src="candidate.image ? '/file/image/' + candidate.image : '/gambar/logo-osis.png'"
                                     :alt="candidate.name"
                                     onerror="this.src='/gambar/logo-osis.png'"
                                     class="max-w-full max-h-full object-contain transition-transform duration-500 hover:scale-105"
                                     style="filter: drop-shadow(0 6px 16px rgba(15,23,42,0.10))">
                            </div>

                            <!-- Slogan -->
                            <div v-if="candidate.slogan" class="my-2.5 px-3 py-1.5 rounded-xl bg-ocean-50/70 border border-ocean-100 text-center font-medium italic text-xs text-ocean-800 leading-snug">
                                &ldquo;@{{ candidate.slogan }}&rdquo;
                            </div>

                            <!-- Name Box -->
                            <div class="p-3.5 my-2.5 rounded-2xl bg-slate-50/80 border border-slate-100 space-y-2.5">
                                <div class="flex items-start justify-between gap-2">
                                    <div>
                                        <span class="block text-[10px] font-bold uppercase tracking-wider text-ocean-600">Calon Ketua</span>
                                        <h3 class="font-display font-bold text-sm sm:text-base text-ocean-950 leading-tight">@{{ candidate.name }}</h3>
                                    </div>
                                    <span class="text-[11px] font-bold text-ocean-900 bg-white border border-slate-200/80 px-2 py-0.5 rounded-md shadow-xs shrink-0">@{{ candidate.class || '—' }}</span>
                                </div>

                                <div v-if="candidate.vice_name" class="pt-2.5 border-t border-slate-200/70 flex items-start justify-between gap-2">
                                    <div>
                                        <span class="block text-[10px] font-bold uppercase tracking-wider text-cloudy-500">Calon Wakil</span>
                                        <h4 class="font-display font-bold text-sm sm:text-base text-ocean-950 leading-tight">@{{ candidate.vice_name }}</h4>
                                    </div>
                                    <span class="text-[11px] font-bold text-ocean-900 bg-white border border-slate-200/80 px-2 py-0.5 rounded-md shadow-xs shrink-0">@{{ candidate.vice_class || '—' }}</span>
                                </div>
                            </div>

                            <!-- Visi -->
                            <div class="mb-2.5 text-left">
                                <span class="text-[10px] font-bold uppercase tracking-wider text-amber-600 flex items-center gap-1 mb-1.5">
                                    <i class="bi bi-lightbulb-fill"></i> Visi
                                </span>
                                <div class="p-2.5 rounded-xl bg-white border border-slate-100 text-xs text-cloudy-700 leading-relaxed max-h-24 overflow-y-auto whitespace-pre-line shadow-[inset_0_1px_2px_rgba(15,23,42,0.02)]">
                                    @{{ candidate.visi || 'Belum diisi oleh kandidat.' }}
                                </div>
                            </div>

                            <!-- Misi -->
                            <div class="mb-2.5 text-left">
                                <span class="text-[10px] font-bold uppercase tracking-wider text-ocean-600 flex items-center gap-1 mb-1.5">
                                    <i class="bi bi-bullseye"></i> Misi
                                </span>
                                <div class="p-2.5 rounded-xl bg-white border border-slate-100 text-xs text-cloudy-700 leading-relaxed max-h-24 overflow-y-auto whitespace-pre-line shadow-[inset_0_1px_2px_rgba(15,23,42,0.02)]">
                                    @{{ candidate.misi || 'Belum diisi oleh kandidat.' }}
                                </div>
                            </div>

                            <!-- Program Kerja -->
                            <div v-if="candidate.work_program" class="mb-3 text-left">
                                <span class="text-[10px] font-bold uppercase tracking-wider text-teal-600 flex items-center gap-1 mb-1.5">
                                    <i class="bi bi-briefcase-fill"></i> Program Kerja
                                </span>
                                <div class="p-2.5 rounded-xl bg-white border border-slate-100 text-xs text-cloudy-700 leading-relaxed max-h-24 overflow-y-auto whitespace-pre-line shadow-[inset_0_1px_2px_rgba(15,23,42,0.02)]">
                                    @{{ candidate.work_program }}
                                </div>
                            </div>
                        </div>

                        <!-- CTA Button -->
                        <div class="pt-3 mt-auto border-t border-slate-100">
                            <a href="/user/login" class="btn-island-primary w-full">
                                <span>Pilih Pasangan Ini</span>
                                <div class="btn-island-icon">
                                    <i class="bi bi-box-arrow-in-down text-white text-sm"></i>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Empty State -->
            <div v-if="osisCandidate.length === 0 && !loading" class="text-center py-16 max-w-sm mx-auto anim-fade-up">
                <div class="bezel-card-shell">
                    <div class="bezel-card-core p-8">
                        <div class="w-16 h-16 mx-auto mb-4 rounded-3xl bg-ocean-50 border border-ocean-100 text-ocean-300 flex items-center justify-center text-2xl">
                            <i class="bi bi-person-slash"></i>
                        </div>
                        <h3 class="font-display font-bold text-lg text-ocean-950 mb-2">Belum ada kandidat</h3>
                        <p class="text-sm text-cloudy-500">Data pasangan calon belum tersedia. Silakan kembali lagi nanti.</p>
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

    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/vue@2/dist/vue.js"></script>

    <script>
        const osisCandidate = {!! \Illuminate\Support\Js::from($osis_candidate) !!};

        new Vue({
            el: '#app',
            data: {
                osisCandidate: osisCandidate,
                loading: false,
            }
        });
    </script>
</body>
</html>
