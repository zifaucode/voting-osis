<!DOCTYPE html>
<html lang="id" class="h-full">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <title>Pendaftaran Pemilih | {{ $web_setting?->web_title ?? 'PEMILOS' }}</title>
    <meta name="description" content="Daftar sebagai pemilih resmi PEMILOS OSIS Digital.">

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
            <span>Pendaftaran Pemilih Resmi</span>
            <span class="text-cloudy-300">•</span>
            <span class="text-cloudy-500 font-normal">{{ $web_setting?->school_name ?? 'SMK / SMA INDONESIA' }}</span>
        </div>
    </header>

    <!-- Main Content -->
    <main class="flex-grow flex items-center justify-center px-4 py-8 sm:py-12">
        <div class="bezel-card-shell w-full max-w-[520px]">
            <div class="bezel-card-core p-6 sm:p-8">
                <!-- Card Header -->
                <div class="text-center mb-6">
                    <div class="w-16 h-16 mx-auto mb-3.5 rounded-3xl bg-gradient-to-br from-white to-ocean-50 border border-white/90 p-2.5 shadow-sm flex items-center justify-center transition-transform duration-500 hover:scale-105">
                        <img src="{{ $web_setting?->logo_image ? asset('gambar/logo/' . $web_setting->logo_image) : asset('gambar/logo-osis.png') }}"
                             alt="Logo OSIS"
                             class="max-w-full max-h-full object-contain">
                    </div>

                    <div class="eyebrow-badge mb-2">
                        <i class="bi bi-person-plus-fill text-ocean-500"></i>
                        <span>Pendaftaran DPT Siswa</span>
                    </div>

                    <h1 class="text-2xl sm:text-3xl font-display font-extrabold text-ocean-950 tracking-tight text-balance">
                        Daftar sebagai Pemilih
                    </h1>
                    <p class="mt-1.5 text-xs sm:text-sm text-cloudy-500 max-w-[340px] mx-auto leading-relaxed">
                        Lengkapi data identitas Anda untuk mendaftar sebagai pemilih resmi dalam PEMILOS Digital.
                    </p>
                </div>

                <!-- Flash Messages -->
                @if(session()->has('message'))
                <div class="mb-5 p-3.5 rounded-2xl bg-ocean-50/90 border border-ocean-200/90 text-ocean-800 text-xs sm:text-sm font-medium flex items-center gap-2.5 shadow-sm">
                    <i class="bi bi-check-circle-fill text-ocean-600 text-base shrink-0"></i>
                    <span>{{ session()->get('message') }}</span>
                </div>
                @endif

                @if ($errors->any())
                <div class="mb-5 p-3.5 rounded-2xl bg-rose-50/90 border border-rose-200/90 text-rose-800 text-xs sm:text-sm font-medium shadow-sm">
                    <div class="flex items-start gap-2">
                        <i class="bi bi-exclamation-triangle-fill text-rose-600 text-base shrink-0 mt-0.5"></i>
                        <ul class="space-y-0.5">
                            @foreach ($errors->all() as $err)
                                <li>{{ $err }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
                @endif

                <!-- Registration Form -->
                <form method="post" action="{{ \Illuminate\Support\Facades\Route::has('new.register') ? route('new.register') : '#' }}" class="space-y-4">
                    @csrf

                    <!-- Full Name -->
                    <div>
                        <label class="block text-xs font-bold text-ocean-900 mb-1.5 flex items-center gap-1.5">
                            <i class="bi bi-person-fill text-ocean-500"></i> Nama Lengkap
                        </label>
                        <input type="text"
                               class="input-island"
                               placeholder="Masukkan nama lengkap"
                               name="name"
                               value="{{ old('name') }}"
                               required>
                    </div>

                    <!-- Age + Phone row -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-ocean-900 mb-1.5 flex items-center gap-1.5">
                                <i class="bi bi-calendar3 text-ocean-500"></i> Usia / Umur
                            </label>
                            <input type="number"
                                   class="input-island"
                                   placeholder="Contoh: 16"
                                   name="age"
                                   value="{{ old('age') }}"
                                   min="10" max="25"
                                   required>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-ocean-900 mb-1.5 flex items-center gap-1.5">
                                <i class="bi bi-phone-fill text-ocean-500"></i> No. WhatsApp / HP
                            </label>
                            <input type="text"
                                   class="input-island"
                                   placeholder="08xxxxxxxxxx"
                                   name="phone"
                                   value="{{ old('phone') }}"
                                   required>
                        </div>
                    </div>

                    <!-- Gender -->
                    <div>
                        <label class="block text-xs font-bold text-ocean-900 mb-1.5 flex items-center gap-1.5">
                            <i class="bi bi-gender-ambiguous text-ocean-500"></i> Jenis Kelamin
                        </label>
                        <select name="gender" class="input-island" required>
                            <option value="">— Pilih Jenis Kelamin —</option>
                            <option value="Laki-Laki" {{ old('gender') == 'Laki-Laki' ? 'selected' : '' }}>Laki-Laki</option>
                            <option value="Perempuan" {{ old('gender') == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                        </select>
                    </div>

                    <!-- Class -->
                    <div>
                        <label class="block text-xs font-bold text-ocean-900 mb-1.5 flex items-center gap-1.5">
                            <i class="bi bi-building text-ocean-500"></i> Kelas / Rombel
                        </label>
                        <input type="text"
                               class="input-island"
                               name="address"
                               placeholder="Contoh: XII RPL 1"
                               value="{{ old('address') }}"
                               required>
                    </div>

                    <!-- Email -->
                    <div>
                        <label class="block text-xs font-bold text-ocean-900 mb-1.5 flex items-center gap-1.5">
                            <i class="bi bi-envelope-fill text-ocean-500"></i> Alamat Email
                        </label>
                        <input type="email"
                               class="input-island"
                               placeholder="email@sekolah.sch.id"
                               name="email"
                               value="{{ old('email') }}"
                               required>
                    </div>

                    <!-- Password -->
                    <div>
                        <label class="block text-xs font-bold text-ocean-900 mb-1.5 flex items-center gap-1.5">
                            <i class="bi bi-lock-fill text-ocean-500"></i> Kata Sandi Akun
                        </label>
                        <input type="password"
                               class="input-island"
                               placeholder="Buat kata sandi akun"
                               name="password"
                               required>
                    </div>

                    <!-- Info tip -->
                    <div class="p-3 rounded-2xl bg-ocean-50/60 border border-dashed border-ocean-200/80 text-ocean-800 text-[11px] sm:text-xs leading-relaxed flex items-start gap-2.5">
                        <i class="bi bi-info-circle-fill text-ocean-500 text-sm shrink-0 mt-0.5"></i>
                        <span>Data yang Anda masukkan akan diverifikasi oleh panitia. Pastikan data yang diisi <strong>sesuai dengan identitas resmi</strong> Anda di sekolah.</span>
                    </div>

                    <!-- Submit -->
                    <button type="submit" class="btn-island-primary w-full mt-2">
                        <span>Selesaikan Pendaftaran</span>
                        <div class="btn-island-icon">
                            <i class="bi bi-person-check-fill text-white text-base"></i>
                        </div>
                    </button>

                    <!-- Nav Links -->
                    <div class="pt-4 border-t border-slate-100 flex items-center justify-between text-xs font-semibold text-cloudy-500">
                        <a href="/" class="inline-flex items-center gap-1.5 hover:text-ocean-600 transition-colors">
                            <i class="bi bi-arrow-left"></i> Beranda
                        </a>
                        <span>
                            Sudah punya akun?
                            <a href="/user/login" class="font-bold text-ocean-600 hover:underline ml-1">Masuk di sini</a>
                        </span>
                    </div>
                </form>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="w-full pb-6 px-4 text-center text-xs text-cloudy-400">
        <div class="inline-flex items-center gap-2 flex-wrap justify-center">
            <span class="font-medium text-ocean-900/80">{{ $web_setting?->web_title ?? 'PEMILOS' }} &copy; {{ date('Y') }}</span>
            <span>•</span>
            <span class="font-medium text-ocean-900">Zidan Fathul</span>
        </div>
    </footer>
</body>
</html>
