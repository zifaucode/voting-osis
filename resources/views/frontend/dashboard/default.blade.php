<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>{{ $web_setting?->web_title ?? 'VOTING OSIS' }} - E-Voting Modern</title>

    <!-- Favicons -->
    <link href="{{ $web_setting?->logo_image ? asset('gambar/logo/' . $web_setting->logo_image) : asset('gambar/logo-osis.png') }}" rel="icon">
    <link href="{{ $web_setting?->logo_image ? asset('gambar/logo/' . $web_setting->logo_image) : asset('gambar/logo-osis.png') }}" rel="apple-touch-icon">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700;800;900&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Vendor CSS Files -->
    <link rel="stylesheet" href="{{ asset('user-page/assets/vendor/bootstrap/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('user-page/assets/vendor/bootstrap-icons/bootstrap-icons.css') }}">
    <link rel="stylesheet" href="{{ asset('user-page/assets/vendor/aos/aos.css') }}">

    <!-- Glassmorphism Stylesheet -->
    <link rel="stylesheet" href="{{ asset('css/glassmorphism.css') }}">
</head>

<body>
    <div class="ambient-wrapper d-flex flex-column" style="min-height: 100vh;">
        <!-- Ambient Glowing Blobs -->
        <div class="ambient-blob blob-1"></div>
        <div class="ambient-blob blob-2"></div>
        <div class="ambient-blob blob-3"></div>

        <!-- Glass Navbar -->
        <header class="glass-navbar">
            <div class="container d-flex align-items-center justify-content-between">
                <a href="/" class="brand-title">
                    <img src="{{ $web_setting?->logo_image ? asset('gambar/logo/' . $web_setting->logo_image) : asset('gambar/logo-osis.png') }}" alt="Logo OSIS">
                    <span>{{ $web_setting?->web_title ?? 'PEMILOS DIGITAL' }}</span>
                </a>

                <nav class="navmenu">
                    <a href="/admin" class="glass-btn-secondary">
                        <i class="bi bi-shield-lock"></i> Login Admin
                    </a>
                </nav>
            </div>
        </header>

        <!-- Main Content -->
        <main class="flex-grow-1 d-flex align-items-center justify-content-center py-5" style="position: relative; z-index: 2;">
            <div class="container d-flex justify-content-center">
                <div class="glass-card text-center p-5 mx-auto" style="max-width: 620px; width: 100%; border-radius: 32px;" data-aos="zoom-in" data-aos-duration="900">
                    <div class="mb-4">
                        <div class="d-inline-flex align-items-center justify-content-center" style="width: 90px; height: 90px; background: rgba(16, 185, 129, 0.12); border-radius: 50%; box-shadow: 0 10px 25px rgba(16, 185, 129, 0.2);">
                            <i class="bi bi-calendar2-week text-success" style="font-size: 2.75rem;"></i>
                        </div>
                    </div>

                    <div class="school-badge mb-3">
                        <i class="bi bi-award-fill mr-1"></i>
                        <span>{{ $web_setting?->school_name ?? 'NAMA SEKOLAH BELUM DIATUR' }}</span>
                    </div>

                    <h2 class="font-weight-bold mb-3" style="font-family: var(--font-heading); font-size: 2.2rem; color: var(--text-main);">
                        Belum Ada Jadwal Pemilihan
                    </h2>

                    <p class="text-muted mb-4" style="line-height: 1.65; font-size: 1.05rem;">
                        Saat ini sesi pemungutan suara belum dibuka atau jadwal pemilihan belum diatur oleh panitia. Silakan hubungi administrator sekolah atau periksa kembali secara berkala.
                    </p>

                    <div>
                        <a href="/" class="glass-btn-primary d-inline-flex align-items-center justify-content-center" style="width: auto; padding: 0.9rem 2.25rem;" onclick="window.location.reload(); return false;">
                            <i class="bi bi-arrow-clockwise mr-2" style="font-size: 1.2rem;"></i> Perbarui Halaman
                        </a>
                    </div>
                </div>
            </div>
        </main>

        <!-- Footer -->
        <footer class="py-4 text-center text-muted" style="position: relative; z-index: 2; border-top: 1px solid var(--glass-border-subtle); background: rgba(255, 255, 255, 0.5); backdrop-filter: blur(10px);">
            <div class="container">
                <small class="d-block font-weight-bold text-dark mb-1">
                    {{ $web_setting?->web_title ?? 'PEMILOS' }} &copy; {{ date('Y') }} • E-Voting OSIS Modern
                </small>
                <small class="text-muted d-inline-flex align-items-center justify-content-center flex-wrap" style="gap: 0.4rem;">
                    <span><strong>Zidan Fathul</strong></span>
                    <span>•</span>
                    <a href="https://instagram.com/zidan_kebanyakan_tugas" target="_blank" rel="noopener noreferrer" class="text-decoration-none font-weight-bold d-inline-flex align-items-center" style="color: #e1306c;">
                        <i class="bi bi-instagram mr-1"></i>@zidan_kebanyakan_tugas
                    </a>
                </small>
            </div>
        </footer>
    </div>

    <!-- Scripts -->
    <script src="{{ asset('user-page/assets/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('user-page/assets/vendor/aos/aos.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            AOS.init({
                duration: 800,
                once: true
            });
        });
    </script>
</body>

</html>
