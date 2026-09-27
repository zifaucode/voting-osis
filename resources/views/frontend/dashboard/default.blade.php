<!DOCTYPE html>
<html lang="id" class="h-full">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>{{ $web_setting?->web_title ?? 'VOTING OSIS' }} - Bilik Suara Digital</title>

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
    <header class="floating-island-nav">
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

        <div class="flex items-center gap-2 sm:gap-3">
            <a href="/admin/login" class="btn-island-secondary" title="Portal Masuk Admin">
                <i class="bi bi-shield-lock text-ocean-500 text-sm"></i>
                <span class="hidden sm:inline">Admin Login</span>
            </a>
        </div>
    </header>

    <!-- Main Standby Notice -->
    <main class="flex-grow flex items-center justify-center px-4 py-28 sm:py-36 max-w-xl mx-auto w-full">
        <div class="bezel-card-shell w-full text-center">
            <div class="bezel-card-core p-8 sm:p-12">
                <div class="w-20 h-20 mx-auto mb-5 rounded-3xl bg-ocean-50 border border-ocean-200 text-ocean-600 flex items-center justify-center text-3xl shadow-sm">
                    <i class="bi bi-calendar2-week"></i>
                </div>

                <div class="eyebrow-badge mb-3">
                    <i class="bi bi-award-fill text-ocean-500"></i>
                    <span>{{ $web_setting?->school_name ?? 'SMK / SMA INDONESIA' }}</span>
                </div>

                <h1 class="text-2xl sm:text-3xl font-display font-extrabold text-ocean-950 tracking-tight text-balance">
                    Belum Ada Jadwal Pemilihan
                </h1>

                <p class="mt-3 text-xs sm:text-sm text-cloudy-600 leading-relaxed text-balance max-w-md mx-auto">
                    Sesi pemungutan suara belum dibuka atau jadwal pemilihan belum diatur oleh panitia. Silakan hubungi pengurus OSIS atau periksa kembali secara berkala.
                </p>

                <div class="mt-8 flex justify-center">
                    <button type="button" onclick="window.location.reload();" class="btn-island-primary">
                        <span>Perbarui Halaman</span>
                        <div class="btn-island-icon">
                            <i class="bi bi-arrow-clockwise text-white text-base"></i>
                        </div>
                    </button>
                </div>
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
</body>

</html>
