<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login Pemilih | {{ $web_setting?->web_title ?? 'PEMILOS' }}</title>

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
        <div class="glass-login-card" style="max-width: 480px;">
            <div class="glass-login-header">
                <div class="glass-login-logo">
                    <img src="{{ $web_setting?->logo_image ? asset('gambar/logo/' . $web_setting->logo_image) : asset('gambar/logo-osis.png') }}" alt="Logo OSIS">
                </div>
                <div class="school-badge mb-2">
                    <i class="bi bi-award-fill mr-1"></i>
                    <span>{{ $web_setting?->school_name ?? 'SMK / SMA INDONESIA' }}</span>
                </div>
                <h1 class="glass-login-title">Bilik Suara Pemilih</h1>
                <p class="glass-login-subtitle">Masukkan Username dan Kode Akses kartu suara untuk memberikan hak pilih Anda</p>
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

            @if ($errors->any())
            <div class="alert alert-warning border-0 shadow-sm mb-4" role="alert" style="border-radius: 14px; background: rgba(245, 158, 11, 0.15); color: #92400e; font-weight: 600;">
                <ul class="mb-0 pl-3">
                    @foreach ($errors->all() as $err)
                        <li>{{ $err }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <form action="{{ route('auth.user') }}" method="post">
                @csrf
                <div class="glass-input-group mb-3">
                    <label class="glass-input-label" for="nisn">
                        <i class="bi bi-person-badge-fill text-muted mr-1"></i> Username
                    </label>
                    <input type="text" 
                           class="glass-input" 
                           id="nisn" 
                           placeholder="Masukkan Username Anda" 
                           name="nisn" 
                           autocomplete="off" 
                           value="{{ old('nisn') }}" 
                           required>
                </div>

                <div class="glass-input-group mb-4">
                    <label class="glass-input-label" for="code_access">
                        <i class="bi bi-key-fill text-muted mr-1"></i> Token / Kode Akses Pemilih
                    </label>
                    <input type="text" 
                           class="glass-input letter-spacing-1 font-weight-bold" 
                           id="code_access" 
                           placeholder="Contoh: USR-82910" 
                           name="code_access" 
                           autocomplete="off" 
                           required>
                </div>

                <button type="submit" class="glass-btn-primary mb-3">
                    <i class="bi bi-box-arrow-in-right mr-1"></i> Masuk ke Bilik Suara
                </button>

                <div style="text-align: center; margin-top: 1.25rem; padding-top: 1rem; border-top: 1px solid rgba(0,0,0,0.06);">
                    <a href="/admin/login" style="color: #64748b; font-size: 0.875rem; text-decoration: none; display: inline-flex; align-items: center; gap: 0.4rem; font-weight: 500; transition: color 0.2s;" onmouseover="this.style.color='#059669'" onmouseout="this.style.color='#64748b'">
                        <i class="bi bi-shield-lock-fill" style="color: #059669; font-size: 1rem;"></i> Masuk sebagai Administrator
                    </a>
                </div>
            </form>
        </div>

        <div class="text-center mt-3 text-muted small" style="position: relative; z-index: 2;">
            <strong>Zidan Fathul</strong> • 
            <a href="https://instagram.com/zidan_kebanyakan_tugas" target="_blank" rel="noopener noreferrer" style="color: #e1306c; text-decoration: none; font-weight: 600;">
                <i class="bi bi-instagram mr-1"></i>@zidan_kebanyakan_tugas
            </a>
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
        }, 5000);
    </script>
</body>

</html>