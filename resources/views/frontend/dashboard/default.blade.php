<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>{{ $web_setting?->web_title ?? 'VOTING OSIS' }}</title>
    
    <!-- Favicons -->
    <link href="{{ $web_setting?->logo_image ? asset('gambar/logo/' . $web_setting->logo_image) : asset('gambar/logo-osis.png') }}" rel="icon">
    <link href="{{ $web_setting?->logo_image ? asset('gambar/logo/' . $web_setting->logo_image) : asset('gambar/logo-osis.png') }}" rel="apple-touch-icon">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;800&family=Manrope:wght@400;500;700&display=swap" rel="stylesheet">

    <!-- Vendor CSS Files -->
    <link rel="stylesheet" href="{{ asset('user-page/assets/vendor/bootstrap/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('user-page/assets/vendor/bootstrap-icons/bootstrap-icons.css') }}">
    <link rel="stylesheet" href="{{ asset('user-page/assets/vendor/aos/aos.css') }}">

    <style>
        :root {
            --primary-color: #2e8b57;
            --primary-light: #4fb66b;
            --bg-color: #f4f9f5;
            --text-main: #1a202c;
            --text-muted: #718096;
        }

        body {
            font-family: 'Manrope', sans-serif;
            background-color: var(--bg-color);
            background-image: 
                radial-gradient(at 40% 20%, hsla(145, 40%, 85%, 1) 0px, transparent 50%),
                radial-gradient(at 80% 0%, hsla(189, 40%, 85%, 1) 0px, transparent 50%),
                radial-gradient(at 0% 50%, hsla(145, 40%, 85%, 1) 0px, transparent 50%);
            background-attachment: fixed;
            color: var(--text-main);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            margin: 0;
            overflow-x: hidden;
        }

        h1, h2, h3, .sitename {
            font-family: 'Outfit', sans-serif;
        }

        .header {
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(10px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.3);
            padding: 1rem 0;
            position: fixed;
            width: 100%;
            top: 0;
            z-index: 1000;
        }

        .sitename {
            font-weight: 800;
            font-size: 1.5rem;
            color: var(--primary-color);
            margin: 0;
            letter-spacing: -0.5px;
        }

        .navmenu {
            margin-left: auto;
        }

        .main {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding-top: 80px; /* Offset for header */
            position: relative;
        }

        .empty-state-card {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(20px);
            border-radius: 32px;
            padding: 4rem 3rem;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.05),
                        inset 0 1px 0 rgba(255, 255, 255, 1);
            border: 1px solid rgba(255, 255, 255, 0.5);
            max-width: 600px;
            width: 90%;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .empty-state-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 6px;
            background: linear-gradient(90deg, var(--primary-color), var(--primary-light));
        }

        .icon-wrapper {
            width: 100px;
            height: 100px;
            background: linear-gradient(135deg, #e6f4ea 0%, #c8e6c9 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 2rem;
            box-shadow: 0 10px 20px rgba(79, 182, 107, 0.2);
            animation: float 6s ease-in-out infinite;
        }

        .icon-wrapper i {
            font-size: 3rem;
            color: var(--primary-color);
        }

        @keyframes float {
            0% { transform: translateY(0px); }
            50% { transform: translateY(-15px); }
            100% { transform: translateY(0px); }
        }

        .school-name {
            font-size: 1rem;
            font-weight: 700;
            color: var(--primary-color);
            margin-bottom: 1rem;
            text-transform: uppercase;
            letter-spacing: 2px;
        }

        .title {
            font-size: 2.5rem;
            font-weight: 800;
            color: var(--text-main);
            margin-bottom: 1.5rem;
            line-height: 1.2;
            letter-spacing: -1px;
        }

        .description {
            font-size: 1.1rem;
            color: var(--text-muted);
            line-height: 1.6;
            margin-bottom: 2.5rem;
        }

        .btn-custom {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            background: var(--primary-color);
            color: white;
            font-weight: 600;
            padding: 0.8rem 2rem;
            border-radius: 50px;
            text-decoration: none;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            border: none;
            box-shadow: 0 4px 15px rgba(46, 139, 87, 0.3);
        }

        .btn-custom:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(46, 139, 87, 0.4);
            color: white;
            background: #246e45;
        }

        .btn-custom i {
            transition: transform 0.3s ease;
        }

        .btn-custom:hover i {
            transform: translateX(4px) rotate(180deg) !important;
        }

        .btn-nav {
            background: rgba(46, 139, 87, 0.1);
            color: var(--primary-color);
            font-weight: 600;
            padding: 0.6rem 1.5rem;
            border-radius: 50px;
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .btn-nav:hover {
            background: var(--primary-color);
            color: white;
        }

        .footer {
            text-align: center;
            padding: 2rem;
            color: var(--text-muted);
            font-size: 0.9rem;
            background: transparent;
        }

        .footer a {
            color: var(--primary-color);
            text-decoration: none;
            font-weight: 600;
        }
        
        /* Decorative elements */
        .decoration {
            position: absolute;
            z-index: -1;
            opacity: 0.6;
        }
        .dec-1 {
            top: 15%;
            left: 10%;
            width: 200px;
            height: 200px;
            border-radius: 50%;
            background: linear-gradient(135deg, #e6f4ea 0%, transparent 100%);
            filter: blur(20px);
            animation: pulse 8s infinite alternate;
        }
        .dec-2 {
            bottom: 20%;
            right: 10%;
            width: 300px;
            height: 300px;
            border-radius: 50%;
            background: linear-gradient(135deg, #e2e8f0 0%, transparent 100%);
            filter: blur(30px);
            animation: pulse 10s infinite alternate-reverse;
        }

        @keyframes pulse {
            0% { transform: scale(1) translate(0, 0); }
            100% { transform: scale(1.1) translate(20px, -20px); }
        }
        
        @media (max-width: 768px) {
            .title { font-size: 2rem; }
            .empty-state-card { padding: 3rem 2rem; }
        }
    </style>
</head>

<body>
    <header class="header">
        <div class="container d-flex align-items-center">
            <a href="/" class="logo d-flex align-items-center text-decoration-none">
                @if($web_setting?->logo_image)
                    <img src="{{ asset('gambar/logo/' . $web_setting->logo_image) }}" alt="Logo" style="height: 40px; margin-right: 10px;">
                @else
                    <img src="{{ asset('gambar/logo-osis.png') }}" alt="Logo" style="height: 40px; margin-right: 10px;">
                @endif
                <h1 class="sitename">{{ $web_setting?->web_title ?? 'VOTING OSIS' }}</h1>
            </a>

            <nav class="navmenu">
                <a href="/admin" class="btn-nav">Login Admin</a>
            </nav>
        </div>
    </header>

    <main class="main">
        <div class="decoration dec-1"></div>
        <div class="decoration dec-2"></div>

        <div class="empty-state-card" data-aos="zoom-in" data-aos-duration="1000">
            <div class="icon-wrapper" data-aos="flip-up" data-aos-delay="200">
                <i class="bi bi-calendar-x"></i>
            </div>
            
            <div class="school-name" data-aos="fade-up" data-aos-delay="300">
                {{ $web_setting?->school_name ?? 'NAMA SEKOLAH' }}
            </div>
            
            <h2 class="title" data-aos="fade-up" data-aos-delay="400">
                Belum Ada Jadwal Pemilihan
            </h2>
            
            <p class="description" data-aos="fade-up" data-aos-delay="500">
                Saat ini tidak ada sesi pemilihan yang aktif. Silahkan hubungi panitia pemilihan sekolah untuk informasi lebih lanjut atau cek kembali nanti.
            </p>
            
            <div data-aos="fade-up" data-aos-delay="600">
                <a href="/" class="btn-custom" onclick="window.location.reload(); return false;">
                    Refresh Halaman <i class="bi bi-arrow-repeat" style="display: inline-block;"></i>
                </a>
            </div>
        </div>
    </main>

    <footer class="footer">
        <div class="container">
            VOTING OSIS by <a href="https://github.com/zifaucode/voting-osis" target="_blank">zifaucode</a>
        </div>
    </footer>

    <!-- Vendor JS Files -->
    <script src="{{ asset('user-page/assets/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('user-page/assets/vendor/aos/aos.js') }}"></script>
    
    <!-- Initialize AOS directly for the default page -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            AOS.init({
                duration: 800,
                easing: 'ease-in-out',
                once: true,
                mirror: false
            });
        });
    </script>
</body>
</html>
