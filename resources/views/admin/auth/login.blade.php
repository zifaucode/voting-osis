<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin Login | Pemilos</title>

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
        <!-- Ambient Glowing Blobs -->
        <div class="ambient-blob blob-1"></div>
        <div class="ambient-blob blob-2"></div>
        <div class="ambient-blob blob-3"></div>

        <div class="glass-login-card">
            <div class="glass-login-header">
                <div class="glass-login-logo">
                    <img src="/gambar/logo-osis.png" alt="Logo OSIS">
                </div>
                <h1 class="glass-login-title">Admin Portal</h1>
                <p class="glass-login-subtitle">Masuk untuk mengelola data pemilihan & paslon</p>
            </div>

            @if (Session::has('success'))
            <div class="alert alert-success border-0 shadow-sm mb-4" role="alert" style="border-radius: 14px; background: rgba(16, 185, 129, 0.15); color: #065f46; font-weight: 600;">
                <i class="bi bi-check-circle-fill mr-2"></i> {{ Session::get('success') }}
            </div>
            @endif

            @if (Session::has('error'))
            <div class="alert alert-danger border-0 shadow-sm mb-4" role="alert" style="border-radius: 14px; background: rgba(239, 68, 68, 0.15); color: #991b1b; font-weight: 600;">
                <i class="bi bi-exclamation-triangle-fill mr-2"></i> {{ Session::get('error') }}
            </div>
            @endif

            <form action="{{ route('auth.admin') }}" method="post">
                @csrf
                <div class="glass-input-group">
                    <label class="glass-input-label" for="username">
                        <i class="bi bi-person-fill text-muted mr-1"></i> Username
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
                        <i class="bi bi-lock-fill text-muted mr-1"></i> Password
                    </label>
                    <input type="password" 
                           class="glass-input" 
                           id="password" 
                           placeholder="Masukkan password" 
                           name="password" 
                           autocomplete="off" 
                           required>
                </div>

                <button type="submit" class="glass-btn-primary mb-3">
                    <i class="bi bi-box-arrow-in-right"></i> Masuk Sekarang
                </button>

                <div class="text-center mt-3">
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