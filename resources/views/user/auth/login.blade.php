<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <title>Login Bilik Suara | {{ $web_setting?->web_title ?? 'PEMILOS' }}</title>
    <meta name="description" content="Otentikasi Pemilih E-Voting OSIS - Bilik Suara Mandiri & Rahasia">

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

    <!-- Glassmorphism & Claymorphism Stylesheet -->
    <link rel="stylesheet" href="{{ asset('css/glassmorphism.css') }}">
</head>

<body>
    <div class="ambient-wrapper glass-auth-wrapper">
        <div class="glass-login-card">
            <!-- Header Section -->
            <div class="glass-login-header">
                <div class="d-flex align-items-center justify-content-center mb-3">
                    <div class="d-inline-flex align-items-center gap-2 px-3 py-1 rounded-pill" style="background: var(--cloudy-sky-light); border: 1.5px solid var(--cloudy-sky); color: var(--ocean-dark); font-size: 0.76rem; font-weight: 800; letter-spacing: 0.5px; text-transform: uppercase; box-shadow: inset 1px 1px 2px rgba(255,255,255,0.9);">
                        <span class="status-dot"></span> Bilik Suara Elektronik
                    </div>
                </div>

                <div class="glass-login-logo">
                    <img src="{{ $web_setting?->logo_image ? asset('gambar/logo/' . $web_setting->logo_image) : asset('gambar/logo-osis.png') }}" alt="Logo OSIS">
                </div>

                <div class="school-badge mb-2">
                    <i class="bi bi-award-fill"></i>
                    <span>{{ $web_setting?->school_name ?? 'SMK / SMA INDONESIA' }}</span>
                </div>

                <h1 class="glass-login-title" style="letter-spacing: -0.03em; text-wrap: balance;">
                    Bilik Suara Pemilih
                </h1>
                <p class="glass-login-subtitle" style="text-wrap: balance; max-width: 380px; margin: 0 auto;">
                    Masukkan Username dan Kode Akses kartu suara Anda untuk memberikan hak suara secara sah dan rahasia.
                </p>
            </div>

            <!-- Flash Notifications -->
            @if (Session::has('success'))
            <div class="alert alert-success border-0 shadow-sm mb-4" role="alert" style="border-radius: var(--clay-radius-sm); background: rgba(40, 114, 161, 0.12); color: var(--ocean-dark); font-weight: 600; border-left: 4px solid var(--ocean-blue) !important;">
                <div class="d-flex align-items-center gap-2">
                    <i class="bi bi-check-circle-fill mr-1" style="color: var(--ocean-blue); font-size: 1.15rem;"></i>
                    <span>{{ Session::get('success') }}</span>
                </div>
            </div>
            @endif

            @if (Session::has('error'))
            <div class="alert alert-danger border-0 shadow-sm mb-4" role="alert" style="border-radius: var(--clay-radius-sm); background: rgba(239, 68, 68, 0.12); color: #991b1b; font-weight: 600; border-left: 4px solid #ef4444 !important;">
                <div class="d-flex align-items-center gap-2">
                    <i class="bi bi-exclamation-triangle-fill mr-1 text-danger" style="font-size: 1.15rem;"></i>
                    <span>{{ Session::get('error') }}</span>
                </div>
            </div>
            @endif

            @if (isset($errors) && $errors->any())
            <div class="alert alert-warning border-0 shadow-sm mb-4" role="alert" style="border-radius: var(--clay-radius-sm); background: rgba(245, 158, 11, 0.12); color: #92400e; font-weight: 600; border-left: 4px solid #f59e0b !important;">
                <div class="d-flex align-items-start gap-2">
                    <i class="bi bi-info-circle-fill mr-1 text-warning" style="font-size: 1.1rem; margin-top: 2px;"></i>
                    <ul class="mb-0 pl-2" style="list-style-type: none;">
                        @foreach ($errors->all() as $err)
                            <li>{{ $err }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
            @endif

            <!-- Auth Form -->
            <form action="{{ route('auth.user') }}" method="post" id="voterLoginForm">
                @csrf
                <div class="glass-input-group mb-3">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <label class="glass-input-label mb-0" for="nisn">
                            <i class="bi bi-person-badge-fill mr-1" style="color: var(--ocean-blue);"></i> Username / NISN
                        </label>
                        <span class="badge" style="background: var(--cloudy-sky-light); color: var(--ocean-dark); font-size: 0.7rem; font-weight: 700; border-radius: 6px;">KARTU PEMILIH</span>
                    </div>
                    <input type="text" 
                           class="glass-input" 
                           id="nisn" 
                           placeholder="Contoh: 12345678" 
                           name="nisn" 
                           autocomplete="username" 
                           value="{{ old('nisn') }}" 
                           autofocus
                           required>
                </div>

                <div class="glass-input-group mb-3">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <label class="glass-input-label mb-0" for="code_access">
                            <i class="bi bi-shield-lock-fill mr-1" style="color: var(--ocean-blue);"></i> Token / Kode Akses
                        </label>
                        <span class="badge" style="background: var(--cloudy-sky-light); color: var(--ocean-dark); font-size: 0.7rem; font-weight: 700; border-radius: 6px;">KODE RAHASIA</span>
                    </div>
                    <div class="glass-input-wrapper">
                        <input type="password" 
                               class="glass-input font-weight-bold" 
                               id="code_access" 
                               placeholder="Contoh: USR-82910" 
                               name="code_access" 
                               autocomplete="off" 
                               style="letter-spacing: 2px; text-transform: uppercase;"
                               required>
                        <button type="button" class="glass-input-toggle" id="toggleCodeBtn" title="Lihat / Sembunyikan Kode">
                            <i class="bi bi-eye" id="toggleEyeIcon"></i>
                        </button>
                    </div>
                </div>

                <!-- Guidance Tip Box -->
                <div class="glass-login-tip">
                    <i class="bi bi-info-circle-fill" style="color: var(--ocean-blue); font-size: 1.05rem; margin-top: 1px; flex-shrink: 0;"></i>
                    <span>Satu kode akses hanya berlaku untuk <strong>1 kali pemilihan</strong> suara. Pastikan Anda menyelesaikan pemilihan hingga tuntas.</span>
                </div>

                <button type="submit" class="glass-btn-primary mb-3" id="submitBtn">
                    <span id="btnText"><i class="bi bi-box-arrow-in-right mr-1"></i> Masuk ke Bilik Suara</span>
                    <span id="btnSpinner" style="display: none;">
                        <span class="spinner-border spinner-border-sm mr-2" role="status" aria-hidden="true"></span>
                        Memverifikasi Hak Suara...
                    </span>
                </button>

                <!-- Navigation Quick Links -->
                <div class="d-flex align-items-center justify-content-between pt-3 mt-1" style="border-top: 1.5px solid var(--cloudy-sky-border);">
                    <a href="/live-count" style="color: var(--text-muted); font-size: 0.84rem; text-decoration: none; display: inline-flex; align-items: center; gap: 0.4rem; font-weight: 700; transition: color 0.2s;" onmouseover="this.style.color='var(--ocean-blue)'" onmouseout="this.style.color='var(--text-muted)'">
                        <i class="bi bi-broadcast" style="color: var(--ocean-blue);"></i> Pantau Live Count
                    </a>
                    <a href="/admin/login" style="color: var(--text-muted); font-size: 0.84rem; text-decoration: none; display: inline-flex; align-items: center; gap: 0.4rem; font-weight: 600; transition: color 0.2s;" onmouseover="this.style.color='var(--ocean-blue)'" onmouseout="this.style.color='var(--text-muted)'">
                        <i class="bi bi-shield-lock-fill" style="color: var(--ocean-blue);"></i> Portal Admin
                    </a>
                </div>
            </form>
        </div>

        <!-- Creator & School Watermark -->
        <div class="text-center mt-3 text-muted small" style="position: relative; z-index: 2;">
            <span style="color: var(--ocean-navy); font-weight: 700;">Zidan Fathul</span> • 
            <a href="https://instagram.com/zidan_kebanyakan_tugas" target="_blank" rel="noopener noreferrer" style="color: #e1306c; text-decoration: none; font-weight: 600;">
                <i class="bi bi-instagram mr-1"></i>@zidan_kebanyakan_tugas
            </a>
        </div>
    </div>

    <!-- Scripts -->
    <script src="{{ asset('admin-page/plugins/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('admin-page/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Auto dismiss alert after 5s
            window.setTimeout(function() {
                $(".alert").fadeTo(300, 0).slideUp(300, function() {
                    $(this).remove();
                });
            }, 5000);

            // Toggle Token Visibility
            var toggleBtn = document.getElementById('toggleCodeBtn');
            var codeInput = document.getElementById('code_access');
            var eyeIcon = document.getElementById('toggleEyeIcon');

            if (toggleBtn && codeInput && eyeIcon) {
                toggleBtn.addEventListener('click', function() {
                    if (codeInput.type === 'password') {
                        codeInput.type = 'text';
                        eyeIcon.className = 'bi bi-eye-slash';
                    } else {
                        codeInput.type = 'password';
                        eyeIcon.className = 'bi bi-eye';
                    }
                });
            }

            // Auto uppercase on code access input
            if (codeInput) {
                codeInput.addEventListener('input', function() {
                    var start = this.selectionStart;
                    var end = this.selectionEnd;
                    this.value = this.value.toUpperCase().replace(/\s+/g, '');
                    this.setSelectionRange(start, end);
                });
            }

            // Double submit prevention with tactile spinner feedback
            var loginForm = document.getElementById('voterLoginForm');
            var submitBtn = document.getElementById('submitBtn');
            var btnText = document.getElementById('btnText');
            var btnSpinner = document.getElementById('btnSpinner');

            if (loginForm && submitBtn) {
                loginForm.addEventListener('submit', function() {
                    submitBtn.disabled = true;
                    submitBtn.style.opacity = '0.85';
                    submitBtn.style.cursor = 'wait';
                    if (btnText) btnText.style.display = 'none';
                    if (btnSpinner) btnSpinner.style.display = 'inline-flex';
                });
            }
        });
    </script>
</body>

</html>