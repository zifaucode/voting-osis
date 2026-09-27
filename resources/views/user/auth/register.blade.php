<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Pendaftaran Pemilih | Pemilos</title>

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
    <div class="ambient-wrapper glass-auth-wrapper py-5">
        <!-- Ambient Glowing Blobs -->
        <div class="ambient-blob blob-1"></div>
        <div class="ambient-blob blob-2"></div>
        <div class="ambient-blob blob-3"></div>

        <div class="glass-login-card" style="max-width: 540px;">
            <div class="glass-login-header">
                <div class="glass-login-logo">
                    <img src="/gambar/logo-osis.png" alt="Logo OSIS">
                </div>
                <h1 class="glass-login-title">Daftar Pemilih Baru</h1>
                <p class="glass-login-subtitle">Lengkapi identitas untuk mendaftar sebagai pemilih</p>
            </div>

            @if(session()->has('message'))
            <div class="alert alert-success border-0 shadow-sm mb-4" role="alert" style="border-radius: 14px; background: rgba(16, 185, 129, 0.15); color: #065f46; font-weight: 600;">
                <i class="bi bi-check-circle-fill mr-2"></i> {{ session()->get('message') }}
            </div>
            @endif

            <form method="post" action="{{ route('new.register') }}">
                @csrf
                <div class="row">
                    <div class="col-md-12">
                        <div class="glass-input-group">
                            <label class="glass-input-label">Nama Lengkap</label>
                            <input type="text" class="glass-input" placeholder="Masukkan nama lengkap" name="name" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="glass-input-group">
                            <label class="glass-input-label">Usia / Umur</label>
                            <input type="number" class="glass-input" placeholder="Contoh: 16" name="age" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="glass-input-group">
                            <label class="glass-input-label">No. Telepon / WA</label>
                            <input type="text" class="glass-input" placeholder="08xxxxxxxxxx" name="phone" required>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="glass-input-group">
                            <label class="glass-input-label">Jenis Kelamin</label>
                            <select name="gender" class="glass-input" required>
                                <option value="">-- Pilih Jenis Kelamin --</option>
                                <option value="Laki-Laki">Laki-Laki</option>
                                <option value="Perempuan">Perempuan</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="glass-input-group">
                            <label class="glass-input-label">Alamat / Kelas</label>
                            <textarea name="address" class="glass-input" rows="2" placeholder="Alamat atau kelas siswa"></textarea>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="glass-input-group">
                            <label class="glass-input-label">Email</label>
                            <input type="email" class="glass-input" placeholder="email@sekolah.sch.id" name="email" required>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="glass-input-group mb-4">
                            <label class="glass-input-label">Password</label>
                            <input type="password" class="glass-input" placeholder="Buat kata sandi akun" name="password" required>
                        </div>
                    </div>
                </div>

                <button type="submit" class="glass-btn-primary mb-3">
                    <i class="bi bi-person-check-fill"></i> Selesaikan Pendaftaran
                </button>

                <div class="d-flex justify-content-between align-items-center mt-3 pt-2 border-top">
                    <a href="/" class="text-muted small text-decoration-none">
                        <i class="bi bi-arrow-left"></i> Beranda
                    </a>
                    <span class="small text-muted">
                        Sudah punya akun? <a href="/user/login" class="font-weight-bold" style="color: var(--primary-emerald); text-decoration: none;">Masuk di sini</a>
                    </span>
                </div>
            </form>
        </div>
    </div>

    <!-- Scripts -->
    <script src="{{ asset('admin-page/plugins/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('admin-page/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
</body>

</html>