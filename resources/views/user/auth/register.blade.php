<!DOCTYPE html>
<html lang="id" class="h-full">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <title>Pendaftaran Pemilih | {{ $web_setting?->web_title ?? 'PEMILOS' }}</title>

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
    <!-- Main Center Content -->
    <main class="flex-grow flex items-center justify-center px-4 py-12">
        <div class="bezel-card-shell w-full max-w-[560px]">
            <div class="bezel-card-core p-6 sm:p-8">
                <!-- Header -->
                <div class="text-center mb-6">
                    <div class="w-16 h-16 mx-auto mb-3.5 rounded-3xl bg-gradient-to-br from-white to-ocean-50 border border-white/90 p-2.5 shadow-sm flex items-center justify-center">
                        <img src="{{ $web_setting?->logo_image ? asset('gambar/logo/' . $web_setting->logo_image) : asset('gambar/logo-osis.png') }}" 
                             alt="Logo OSIS" 
                             class="max-w-full max-h-full object-contain">
                    </div>

                    <div class="eyebrow-badge mb-2">
                        <i class="bi bi-person-plus-fill text-ocean-500"></i>
                        <span>Pendaftaran DPT Siswa</span>
                    </div>

                    <h1 class="text-2xl sm:text-3xl font-display font-extrabold text-ocean-950 tracking-tight">
                        Daftar Pemilih Baru
                    </h1>
                    <p class="mt-1 text-xs sm:text-sm text-cloudy-500">
                        Lengkapi data identitas Anda untuk mendaftar sebagai pemilih resmi.
                    </p>
                </div>

                @if(session()->has('message'))
                <div class="mb-5 p-3.5 rounded-2xl bg-ocean-50/90 border border-ocean-200/90 text-ocean-800 text-xs sm:text-sm font-medium flex items-center gap-2.5 shadow-sm">
                    <i class="bi bi-check-circle-fill text-ocean-600 text-base shrink-0"></i>
                    <span>{{ session()->get('message') }}</span>
                </div>
                @endif

                <form method="post" action="{{ \Illuminate\Support\Facades\Route::has('new.register') ? route('new.register') : '#' }}" class="space-y-3.5">
                    @csrf
                    <div>
                        <label class="block text-xs font-bold text-ocean-900 mb-1">Nama Lengkap</label>
                        <input type="text" class="input-island" placeholder="Masukkan nama lengkap" name="name" required>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
                        <div>
                            <label class="block text-xs font-bold text-ocean-900 mb-1">Usia / Umur</label>
                            <input type="number" class="input-island" placeholder="Contoh: 16" name="age" required>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-ocean-900 mb-1">No. WhatsApp / HP</label>
                            <input type="text" class="input-island" placeholder="08xxxxxxxxxx" name="phone" required>
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-ocean-900 mb-1">Jenis Kelamin</label>
                        <select name="gender" class="input-island" required>
                            <option value="">-- Pilih Jenis Kelamin --</option>
                            <option value="Laki-Laki">Laki-Laki</option>
                            <option value="Perempuan">Perempuan</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-ocean-900 mb-1">Kelas / Rombel</label>
                        <input type="text" name="address" class="input-island" placeholder="Contoh: XII RPL 1" required>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-ocean-900 mb-1">Alamat Email</label>
                        <input type="email" class="input-island" placeholder="email@sekolah.sch.id" name="email" required>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-ocean-900 mb-1">Kata Sandi Akun</label>
                        <input type="password" class="input-island" placeholder="Buat kata sandi akun" name="password" required>
                    </div>

                    <button type="submit" class="btn-island-primary w-full mt-2">
                        <span>Selesaikan Pendaftaran</span>
                        <div class="btn-island-icon">
                            <i class="bi bi-person-check-fill text-white text-base"></i>
                        </div>
                    </button>

                    <div class="pt-4 border-t border-slate-100 flex items-center justify-between text-xs text-cloudy-500">
                        <a href="/" class="inline-flex items-center gap-1.5 hover:text-ocean-600 transition-colors">
                            <i class="bi bi-arrow-left"></i> Beranda
                        </a>
                        <span>
                            Sudah punya akun? <a href="/user/login" class="font-bold text-ocean-600 hover:underline">Masuk di sini</a>
                        </span>
                    </div>
                </form>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="w-full pb-6 px-4 text-center text-xs text-cloudy-400">
        <span class="font-medium text-ocean-900/80">{{ $web_setting?->web_title ?? 'PEMILOS' }} &copy; {{ date('Y') }}</span>
    </footer>
</body>

</html>