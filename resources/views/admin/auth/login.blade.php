<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <title>Admin Portal | {{ $web_setting?->web_title ?? 'PEMILOS' }}</title>

    <!-- Favicons -->
    <link href="{{ $web_setting?->logo_image ? asset('gambar/logo/' . $web_setting->logo_image) : asset('gambar/logo-osis.png') }}" rel="icon">
    <link href="{{ $web_setting?->logo_image ? asset('gambar/logo/' . $web_setting->logo_image) : asset('gambar/logo-osis.png') }}" rel="apple-touch-icon">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700;800;900&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Bootstrap & Icons -->
    <link rel="stylesheet" href="{{ asset('user-page/assets/vendor/bootstrap/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('user-page/assets/vendor/bootstrap-icons/bootstrap-icons.css') }}">
    <link rel="stylesheet" href="{{ asset('admin-page/plugins/fontawesome-free/css/all.min.css') }}">

    <!-- Glassmorphism Stylesheet -->
    <link rel="stylesheet" href="{{ asset('css/glassmorphism.css') }}">
</head>

<body>
    <div class="ambient-wrapper glass-auth-wrapper">
        <div class="glass-login-card">
            <div class="glass-login-header">
                <div class="glass-login-logo">
                    <img src="{{ $web_setting?->logo_image ? asset('gambar/logo/' . $web_setting->logo_image) : asset('gambar/logo-osis.png') }}" alt="Logo OSIS">
                </div>
                <div class="school-badge mb-2">
                    <i class="bi bi-shield-lock-fill"></i>
                    <span>ADMINISTRASI UTAMA</span>
                </div>
                <h1 class="glass-login-title">Admin Portal</h1>
                <p class="glass-login-subtitle">Masuk untuk mengelola data pemilihan, paslon, dan pemilih</p>
            </div>

            @if (Session::has('success'))
            <div class="alert alert-success border-0 shadow-sm mb-4" role="alert" style="border-radius: var(--clay-radius-sm); background: rgba(40, 114, 161, 0.12); color: var(--ocean-dark); font-weight: 600; border-left: 4px solid var(--ocean-blue) !important;">
                <i class="bi bi-check-circle-fill mr-2" style="color: var(--ocean-blue);"></i> {{ Session::get('success') }}
            </div>
            @endif

            @if (Session::has('error'))
            <div class="alert alert-danger border-0 shadow-sm mb-4" role="alert" style="border-radius: var(--clay-radius-sm); background: rgba(239, 68, 68, 0.12); color: #991b1b; font-weight: 600; border-left: 4px solid #ef4444 !important;">
                <i class="bi bi-exclamation-triangle-fill mr-2 text-danger"></i> {{ Session::get('error') }}
            </div>
            @endif

            <form action="{{ route('auth.admin') }}" method="post">
                @csrf
                <div class="glass-input-group mb-3">
                    <label class="glass-input-label" for="username">
                        <i class="bi bi-person-fill mr-1" style="color: var(--ocean-blue);"></i> Username Administrator
                    </label>
                    <input type="text" 
                           class="glass-input" 
                           id="username" 
                           placeholder="Masukkan username" 
                           name="username" 
                           autocomplete="off" 
                           value="{{ old('username') }}" 
                           required>
                </div>

                <div class="glass-input-group mb-4">
                    <label class="glass-input-label" for="password">
                        <i class="bi bi-lock-fill mr-1" style="color: var(--ocean-blue);"></i> Kata Sandi
                    </label>
                    <input type="password" 
                           class="glass-input" 
                           id="password" 
                           placeholder="Masukkan kata sandi" 
                           name="password" 
                           autocomplete="off" 
                           required>
                </div>

                <button type="submit" class="glass-btn-primary mb-3">
                    <i class="bi bi-box-arrow-in-right mr-1"></i> Masuk Sekarang
                </button>

                <div class="text-center mt-3 pt-2 border-top" style="border-color: var(--cloudy-sky-border) !important;">
                    <a href="/" class="text-muted small text-decoration-none font-weight-bold" style="transition: color 0.2s;">
                        <i class="bi bi-arrow-left"></i> Kembali ke Beranda Pemilihan
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Scripts -->
    <script src="{{ asset('admin-page/plugins/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('admin-page/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script>
        window.setTimeout(function() {
            $(".alert").fadeTo(300, 0).slideUp(300, function() {
                $(this).remove();
            });
        }, 4000);
    </script>
</body>

</html>