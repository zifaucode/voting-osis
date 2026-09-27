<!DOCTYPE html>
<html lang="id" class="h-full">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <title>Bilik Suara Digital | {{ $web_setting?->web_title ?? 'PEMILOS' }}</title>
    <meta name="description" content="Portal Otentikasi Pemilih E-Voting OSIS - Bilik Suara Mandiri, Sah, dan Rahasia">

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
    <!-- Top Micro Banner -->
    <header class="w-full pt-6 px-4 flex justify-center">
        <div class="inline-flex items-center gap-2.5 px-4 py-1.5 rounded-full bg-white/80 border border-white/90 shadow-sm backdrop-blur-md text-xs font-semibold text-ocean-800">
            <span class="w-2 h-2 rounded-full bg-ocean-500 animate-pulse shadow-[0_0_8px_rgba(40,114,161,0.6)]"></span>
            <span>Bilik Suara Elektronik Terverifikasi</span>
            <span class="text-cloudy-300">•</span>
            <span class="text-cloudy-500 font-normal">{{ $web_setting?->school_name ?? 'SMK / SMA INDONESIA' }}</span>
        </div>
    </header>

    <!-- Main Auth Center Content -->
    <main class="flex-grow flex items-center justify-center px-4 py-8 sm:py-12">
        <div class="bezel-card-shell w-full max-w-[460px]">
            <div class="bezel-card-core">
                <!-- Card Header -->
                <div class="text-center mb-6">
                    <div class="w-20 h-20 mx-auto mb-4 rounded-3xl bg-gradient-to-br from-white to-ocean-50 border border-white/90 p-3 shadow-[0_10px_25px_rgba(40,114,161,0.08),inset_0_1px_2px_rgba(255,255,255,1)] flex items-center justify-center transition-transform duration-500 hover:scale-105">
                        <img src="{{ $web_setting?->logo_image ? asset('gambar/logo/' . $web_setting->logo_image) : asset('gambar/logo-osis.png') }}" 
                             alt="Logo OSIS" 
                             class="max-w-full max-h-full object-contain filter drop-shadow">
                    </div>

                    <div class="eyebrow-badge mb-2">
                        <i class="bi bi-award-fill text-ocean-500"></i>
                        <span>{{ $web_setting?->school_name ?? 'PEMILOS DIGITAL' }}</span>
                    </div>

                    <h1 class="text-2xl sm:text-3xl font-display font-extrabold text-ocean-950 tracking-tight text-balance">
                        Bilik Suara Pemilih
                    </h1>
                    <p class="mt-1.5 text-xs sm:text-sm text-cloudy-500 text-balance leading-relaxed max-w-[340px] mx-auto">
                        Masukkan kredensial kartu suara untuk memberikan hak pilih Anda secara sah, mandiri, dan rahasia.
                    </p>
                </div>

                <!-- Flash Message Alerts -->
                @if (Session::has('success'))
                <div class="mb-5 p-3.5 rounded-2xl bg-ocean-50/90 border border-ocean-200/90 text-ocean-800 text-xs sm:text-sm font-medium flex items-center gap-2.5 shadow-sm">
                    <i class="bi bi-check-circle-fill text-ocean-600 text-base shrink-0"></i>
                    <span>{{ Session::get('success') }}</span>
                </div>
                @endif

                @if (Session::has('error'))
                <div class="mb-5 p-3.5 rounded-2xl bg-rose-50/90 border border-rose-200/90 text-rose-800 text-xs sm:text-sm font-medium flex items-center gap-2.5 shadow-sm">
                    <i class="bi bi-exclamation-triangle-fill text-rose-600 text-base shrink-0"></i>
                    <span>{{ Session::get('error') }}</span>
                </div>
                @endif

                @if (isset($errors) && $errors->any())
                <div class="mb-5 p-3.5 rounded-2xl bg-amber-50/90 border border-amber-200/90 text-amber-900 text-xs sm:text-sm font-medium shadow-sm">
                    <div class="flex items-start gap-2">
                        <i class="bi bi-info-circle-fill text-amber-600 text-base shrink-0 mt-0.5"></i>
                        <ul class="space-y-0.5">
                            @foreach ($errors->all() as $err)
                                <li>{{ $err }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
                @endif

                <!-- Auth Form -->
                <form action="{{ route('auth.user') }}" method="post" id="voterLoginForm" class="space-y-4">
                    @csrf
                    <div>
                        <div class="flex justify-between items-center mb-1.5 px-0.5">
                            <label for="nisn" class="text-xs sm:text-sm font-bold text-ocean-900 flex items-center gap-1.5">
                                <i class="bi bi-person-badge-fill text-ocean-500"></i> Username
                            </label>
                            <span class="text-[10px] font-bold uppercase tracking-wider text-ocean-700 bg-ocean-50 border border-ocean-200/60 px-2 py-0.5 rounded-md">Wajib</span>
                        </div>
                        <input type="text" 
                               class="input-island" 
                               id="nisn" 
                               placeholder="Contoh: 12345678" 
                               name="nisn" 
                               autocomplete="username" 
                               value="{{ old('nisn') }}" 
                               autofocus
                               required>
                    </div>

                    <div>
                        <div class="flex justify-between items-center mb-1.5 px-0.5">
                            <label for="code_access" class="text-xs sm:text-sm font-bold text-ocean-900 flex items-center gap-1.5">
                                <i class="bi bi-shield-lock-fill text-ocean-500"></i> Token / Kode Akses
                            </label>
                            <span class="text-[10px] font-bold uppercase tracking-wider text-ocean-700 bg-ocean-50 border border-ocean-200/60 px-2 py-0.5 rounded-md">Rahasia</span>
                        </div>
                        <div class="relative flex items-center">
                            <input type="password" 
                                   class="input-island pr-11 font-mono tracking-widest uppercase font-bold" 
                                   id="code_access" 
                                   placeholder="Contoh: 123456" 
                                   name="code_access" 
                                   autocomplete="off" 
                                   required>
                            <button type="button" 
                                    id="toggleCodeBtn" 
                                    class="absolute right-3 p-1.5 text-cloudy-400 hover:text-ocean-600 transition-colors duration-200 focus:outline-none"
                                    title="Tampilkan / Sembunyikan Kode">
                                <i class="bi bi-eye text-lg" id="toggleEyeIcon"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Guidance Tip Box -->
                    <div class="p-3 rounded-2xl bg-ocean-50/60 border border-dashed border-ocean-200/80 text-ocean-800 text-[11px] sm:text-xs leading-relaxed flex items-start gap-2.5">
                        <i class="bi bi-shield-check text-ocean-600 text-sm shrink-0 mt-0.5"></i>
                        <span>Satu kode akses hanya berlaku untuk <strong>1 kali pemungutan suara</strong>. Pastikan Anda menyelesaikan pilihan sebelum menutup jendela.</span>
                    </div>

                    <!-- Button-in-Button CTA -->
                    <button type="submit" id="submitBtn" class="btn-island-primary w-full mt-2">
                        <span id="btnText" class="flex items-center gap-2">
                            <span>Masuk ke Bilik Suara</span>
                        </span>
                        <span id="btnSpinner" class="hidden items-center gap-2">
                            <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                            </svg>
                            <span>Memverifikasi Hak Suara...</span>
                        </span>
                        <div class="btn-island-icon">
                            <i class="bi bi-arrow-right text-base text-white"></i>
                        </div>
                    </button>

                    <!-- Split Navigation Links -->
                    <div class="pt-4 border-t border-slate-100 flex items-center justify-between text-xs font-semibold text-cloudy-500">
                        <a href="/live-count" class="inline-flex items-center gap-1.5 hover:text-ocean-600 transition-colors">
                            <i class="bi bi-broadcast text-ocean-500"></i> Pantau Live Count
                        </a>
                        <a href="/admin/login" class="inline-flex items-center gap-1.5 hover:text-ocean-600 transition-colors">
                            <i class="bi bi-shield-lock-fill text-ocean-500"></i> Portal Admin
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </main>

    <!-- Micro Footer -->
    <footer class="w-full pb-6 px-4 text-center text-xs text-cloudy-400">
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

    <!-- Interactive Client Script -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Password / Token visibility toggle
            var toggleBtn = document.getElementById('toggleCodeBtn');
            var codeInput = document.getElementById('code_access');
            var eyeIcon = document.getElementById('toggleEyeIcon');

            if (toggleBtn && codeInput && eyeIcon) {
                toggleBtn.addEventListener('click', function() {
                    if (codeInput.type === 'password') {
                        codeInput.type = 'text';
                        eyeIcon.className = 'bi bi-eye-slash text-lg text-ocean-600';
                    } else {
                        codeInput.type = 'password';
                        eyeIcon.className = 'bi bi-eye text-lg text-cloudy-400';
                    }
                });
            }

            // Auto uppercase & space-strip on token entry
            if (codeInput) {
                codeInput.addEventListener('input', function() {
                    var start = this.selectionStart;
                    var end = this.selectionEnd;
                    this.value = this.value.toUpperCase().replace(/\s+/g, '');
                    this.setSelectionRange(start, end);
                });
            }

            // Tactile Loading State on Submit
            var loginForm = document.getElementById('voterLoginForm');
            var submitBtn = document.getElementById('submitBtn');
            var btnText = document.getElementById('btnText');
            var btnSpinner = document.getElementById('btnSpinner');

            if (loginForm && submitBtn) {
                loginForm.addEventListener('submit', function() {
                    submitBtn.disabled = true;
                    submitBtn.classList.add('opacity-80', 'cursor-wait');
                    if (btnText) btnText.classList.add('hidden');
                    if (btnSpinner) {
                        btnSpinner.classList.remove('hidden');
                        btnSpinner.classList.add('flex');
                    }
                });
            }
        });
    </script>
</body>

</html>