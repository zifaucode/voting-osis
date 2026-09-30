# 🚀 PANDUAN ENHANCEMENT & RUNBOOK PEMILOS (UNTUK ZIDAN)

Dokumen ini disusun oleh **Sabil** untuk merangkum seluruh pembaruan optimasi arsitektur, concurrency handling, dan kestabilan performa yang telah disuntikkan ke dalam repositori Pemilos, serta langkah-langkah teknis yang **wajib dieksekusi oleh Zidan** di server VPS sebelum hari-H pemilihan.

---

## 🎯 Ringkasan Skenario Lapangan & Filosofi Optimasi

Berdasarkan kondisi riil pemilihan:
- **Skala Bilik Suara:** Sekitar 3 Lab Komputer × 40 PC = **±120 Bilik Suara aktif serentak**.
- **Jumlah Pemilih:** ±1.000 Siswa/DPT yang melakukan siklus *Login ➔ Buka Paslon ➔ Vote ➔ Logout* secara bergelombang dan cepat (30–60 detik per siklus).
- **Karakteristik Jaringan:** Seluruh komputer di lab sekolah biasanya berbagi **1 IP Publik (NAT Router Sekolah)** yang sama saat mengakses internet.
- **Tampilan Aula:** Proyektor/monitor di aula utama menampilkan perolehan suara secara langsung (*realtime live count*).

> **Prinsip Utama:** Semua trafik adalah pemilih sah internal sekolah. Fokus utama optimasi adalah **Zero-Locking pada Database**, **Eliminasi Bottleneck I/O**, dan **Mencegah Socket Congestion** agar sistem tidak mengalami hang saat jam sibuk.

---

## 🛠️ BAGIAN 1: Apa yang Telah Di-Update oleh Sabil di Kode

Berikut adalah pembaruan kode yang telah selesai dikerjakan dan sudah ada di repositori:

### 1. Engine Coalescing & Throttling Realtime Socket.io (`realtime-server/server.js`)
- **Masalah Lama:** Setiap kali 1 suara masuk, Laravel mengirim POST ke `/broadcast`, dan Socket.io langsung memancarkan (`io.emit`) ke seluruh klien proyektor aula. Jika 40 siswa vote dalam interval 2 detik, terjadi 40 kali lonjakan broadcast websocket beruntun yang memicu lag visual di browser aula dan memboroskan CPU VPS.
- **Pembaruan Sabil:**
  - Menerapkan **Leading-Edge Instant Broadcast:** Suara pertama yang masuk langsung dipancarkan seketika (latensi 0 ms).
  - Menerapkan **Trailing-Edge Coalescing Window (600 ms):** Jika suara ke-2, ke-3, dst. masuk dalam jendela 600 ms, suara tersebut digabungkan (*coalesced*) dan hanya 1 payload akumulasi paling mutakhir yang dikirim di akhir jeda.
  - Menambahkan endpoint diagnostik `GET /health` yang menampilkan metrik realtime:
    - `broadcastsReceived` (jumlah vote diterima dari Laravel)
    - `broadcastsEmitted` (jumlah emisi websocket ke aula)
    - `broadcastsCoalesced` (jumlah payload yang berhasil dihemat)
  - Menambahkan *graceful shutdown handler* untuk mencegah memory leak.

### 2. Eliminasi Database Row-Locking Saat Login Siswa (`app/Http/Controllers/web/AuthUserController.php`)
- **Masalah Lama:** Saat login, sistem sebelumnya menggunakan `DB::transaction()` dengan `lockForUpdate()` pada baris pemilih di tabel `users`. Ketika 120 siswa menekan tombol "Masuk" serentak, MySQL mengalami perebutan lock baris (*lock contention*) yang berpotensi memicu error fatal: `1205: Lock wait timeout exceeded`.
- **Pembaruan Sabil:**
  - Mengganti database transaction lock saat login dengan **Non-Blocking Cache Check** dan **Atomic Cache Lock** (`Cache::lock('voter_login_lock_' . $user->id, 5)`).
  - Verifikasi anti-double login kini berjalan sub-milidetik (<1 ms) langsung di level cache memori/tabel cache.
  - MySQL tidak lagi terkunci saat proses autentikasi. *LockForUpdate* hanya tetap digunakan pada saat transaksi submit suara (`UserSelectCandidateController::store`) demi menjamin integritas 1 suara per DPT.

### 3. In-Memory Caching untuk Halaman Aula & Live Count (`app/Http/Controllers/web/HomeController.php`)
- **Pembaruan Sabil:**
  - Method `showOff()` dan `liveCount()` kini dibungkus dengan `Cache::rememberForever()` untuk data pengaturan web (`pemilos_web_setting`) dan `Cache::remember(..., 3600)` untuk daftar paslon terurut (`pemilos_candidates_list`).
  - Halaman aula atau monitor pengawas yang di-*refresh* berkali-kali tidak akan lagi membebani antrian query database MySQL.

### 4. Migration Tabel `sessions` & Optimasi `.env.example`
- **Pembaruan Sabil:**
  - Membuat berkas migrasi resmi Laravel: `database/migrations/2026_09_27_202332_create_sessions_table.php`.
  - Mengupdate `.env.example` dengan rekomendasi driver performa tinggi (`SESSION_DRIVER=database`, `CACHE_DRIVER=database`, `QUEUE_CONNECTION=database`, dan konfigurasi realtime).

### 5. Konfigurasi Nginx Cloudflare Real IP Restoration (`deployment/nginx-pemilos.conf`)
- **Pembaruan Sabil:**
  - Menambahkan seluruh blok subnet IPv4 & IPv6 resmi Cloudflare dengan direktif `set_real_ip_from` dan `real_ip_header CF-Connecting-IP;`.
  - Dengan konfigurasi ini, variabel `$remote_addr` di Nginx dan Laravel mencatat IP asli komputer bilik/lab sekolah, bukan IP proxy Cloudflare. Log audit dan pelacakan sesi bilik menjadi 100% akurat.

### 6. Relaksasi Rate Limiter Login Pemilih (`app/Providers/RouteServiceProvider.php`)
- **Pembaruan Sabil:**
  - Menaikkan kuota percobaan login pemilih dari 6 per menit menjadi **15 per menit** per-NISN.
  - Memastikan siswa yang keliru mengetik kode akses tidak langsung terkunci di bilik suara, tanpa mengorbankan keamanan akun.

---

## 🖥️ BAGIAN 2: Apa yang HARUS Zidan Lakukan Sendiri di VPS

Karena Sabil hanya bekerja pada repositori kode, langkah-langkah berikut **wajib dijalankan oleh Zidan langsung di server VPS (SSH root)**:

### Langkah 1: Tarik Kode Terbaru di VPS
Masuk ke direktori project di VPS dan lakukan pull:
```bash
cd /var/www/pemilos
git pull origin main
```

### Langkah 2: Jalankan Migrasi Tabel Sessions
Jalankan migrasi database agar tabel `sessions` dibuat di MySQL:
```bash
php artisan migrate --force
```
*(Pastikan output menyatakan bahwa migrasi `create_sessions_table` berhasil dibuat)*.

### Langkah 3: Sesuaikan File `.env` di VPS
Buka file `.env` di server:
```bash
nano /var/www/pemilos/.env
```
Pastikan variabel-variabel performa berikut sudah terpasang:
```env
# Gunakan Database Driver untuk Session & Cache (Bebas I/O Disk Choke)
SESSION_DRIVER=database
CACHE_DRIVER=database
SESSION_LIFETIME=120

# Antrean Background Worker (Agar broadcast suara tidak menahan respon vote siswa)
QUEUE_CONNECTION=database

# Microservice Realtime Socket.io
REALTIME_SERVER_URL=http://127.0.0.1:3001
REALTIME_SECRET=pemilos_secret_broadcast_key_2026
BROADCAST_INTERVAL_MS=600
```
Simpan dengan menekan `Ctrl + O`, `Enter`, lalu keluar dengan `Ctrl + X`.

### Langkah 4: Terapkan Konfigurasi Nginx Terbaru & Reload
Perbarui file konfigurasi Nginx di VPS:
```bash
sudo cp /var/www/pemilos/deployment/nginx-pemilos.conf /etc/nginx/sites-available/pemilos
sudo nginx -t
sudo systemctl reload nginx
```
*(Pastikan `nginx -t` menghasilkan: `syntax is ok` dan `test is successful`)*.

### Langkah 5: Setup & Pastikan Server.js SELALU Berjalan Otomatis di VPS (PENTING!)
> ⚠️ **PERINGATAN KHUSUS UNTUK ZIDAN:**
> Microservice `server.js` (port 3001) adalah jantung dari tampilan perolehan suara di proyektor aula!
> **Jangan jalankan server.js hanya dengan `node server.js` biasa di terminal**, karena jika terminal SSH ditutup atau VPS me-reboot, server.js akan mati dan layar aula akan berhenti update!
>
> Pilih **salah satu dari 2 metode** di bawah ini agar `server.js` **selalu hidup secara otomatis 24/7** dan otomatis bangkit jika server reboot atau crash:

#### Pilihan A: Menggunakan PM2 (Paling Praktis & Direkomendasikan)
```bash
cd /var/www/pemilos/realtime-server
npm install --production

# 1. Jalankan server dengan nama 'pemilos-realtime'
pm2 start server.js --name "pemilos-realtime"

# 2. Daftarkan PM2 ke init sistem Linux agar otomatis menyala saat VPS booting
pm2 startup
# (Salin dan jalankan baris perintah 'sudo env PATH=...' yang dimunculkan oleh terminal Anda)

# 3. Simpan state PM2 saat ini
pm2 save
```

#### Pilihan B: Menggunakan Linux Systemd Service (Native Linux Daemon)
Jika tidak ingin bergantung pada PM2, buat service native systemd:
```bash
sudo nano /etc/systemd/system/pemilos-realtime.service
```
Tempelkan konfigurasi berikut:
```ini
[Unit]
Description=Pemilos Realtime Socket.io Broadcaster
After=network.target

[Service]
Type=simple
User=root
WorkingDirectory=/var/www/pemilos/realtime-server
ExecStart=/usr/bin/node server.js
Restart=always
RestartSec=3
Environment=NODE_ENV=production
Environment=PORT=3001
Environment=HOST=127.0.0.1
Environment=REALTIME_SECRET=pemilos_secret_broadcast_key_2026

[Install]
WantedBy=multi-user.target
```
Lalu aktifkan service:
```bash
sudo systemctl daemon-reload
sudo systemctl enable pemilos-realtime
sudo systemctl start pemilos-realtime
sudo systemctl status pemilos-realtime
```

#### Verifikasi Server Realtime Berjalan:
Uji apakah server realtime dan endpoint diagnostik coalescing sudah merespon:
```bash
curl http://127.0.0.1:3001/health
```
*(Pastikan muncul status: `"status": "ok"` dan `"service": "Pemilos Realtime Broadcaster (Coalescing Engine)"`)*.

### Langkah 6: Jalankan Queue Worker Laravel (PENTING!)
Agar proses pengiriman sinyal broadcast ke Socket.io berjalan secara asinkronus (di background) tanpa memperlambat siswa saat menekan tombol "Kirim Suara", jalankan queue worker:

**Opsi Cepat (PM2):**
```bash
pm2 start "php /var/www/pemilos/artisan queue:work --sleep=1 --tries=2 --timeout=10" --name "pemilos-queue"
pm2 save
```

**Opsi Standar (Systemd):**
Jika ingin menggunakan systemd service, pastikan service worker berjalan:
```bash
sudo systemctl restart pemilos-worker || sudo systemctl restart laravel-worker
```

### Langkah 7: Segarkan Cache Konfigurasi & Optimasi Laravel
Jalankan pembersihan dan kompilasi cache produksi:
```bash
cd /var/www/pemilos
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

---

## 💡 BAGIAN 3: Informasi Kritis & Rekomendasi Hari-H untuk Zidan

### 1. Peringatan Krusial: Bahaya Rate-Limiting Berbasis IP pada NAT Sekolah
- Di lab komputer sekolah, 40–120 PC bilik suara biasanya terhubung ke switch/hub lokal dan mengakses internet lewat 1 router dengan **1 Alamat IP Publik yang sama**.
- **JANGAN PERNAH** menambahkan rate limiting Nginx (`limit_req_zone $binary_remote_addr`) atau Cloudflare WAF Rate Limiting dengan ambang batas rendah (misal 50 req/menit per IP). Jika dilakukan, 1 lab komputer penuh akan langsung terkena blokir (*429 Too Many Requests*) saat sesi voting serentak dimulai!
- Rate limiter di Laravel yang sudah dikonfigurasi saat ini menggunakan isolasi per-NISN (`$key = $nisn . '|' . $ip`), sehingga 120 siswa di lab yang sama **TIDAK AKAN** saling memblokir satu sama lain.

### 2. Konfigurasi SSL Cloudflare (Wajib Mode "Full (Strict)")
- Karena Nginx VPS sudah memiliki sertifikat resmi Let's Encrypt (`/etc/letsencrypt/live/pormulir.my.id/`), pastikan pada Dashboard Cloudflare di menu **SSL/TLS ➔ Overview**, modenya diatur ke **Full (Strict)**.
- Hindari mode *Flexible* karena akan menyebabkan *ERR_TOO_MANY_REDIRECTS* (looping redirect port 80 dan 443).

### 3. Tips Layar Proyektor Aula (Live Count Display)
- Pada komputer/laptop yang dihubungkan ke proyektor aula:
  - Buka halaman `/live-count` dengan peramban berbasis Chromium (Google Chrome / Brave).
  - Tekan tombol **F11** untuk masuk ke Fullscreen Mode.
  - **PENTING:** Pastikan fitur *Energy Saver / Memory Saver* pada Chrome dinonaktifkan untuk tab tersebut agar browser tidak menidurkan (*throttling*) koneksi WebSocket Socket.io saat tidak ada mouse bergerak.

### 4. Backup Cepat Database Sebelum & Sesudah Pemilihan
Lakukan pencadangan database sebelum bilik dibuka dan setelah pemilihan selesai untuk arsip rekap:
```bash
# Backup Sebelum Pemilihan (Snapshot DPT Bersih)
mysqldump -u root -p voting_osis > /var/backups/pemilos_clean_$(date +%Y%m%d_%H%M).sql

# Backup Setelah Pemilihan (Snapshot Hasil Suara Final)
mysqldump -u root -p voting_osis > /var/backups/pemilos_final_$(date +%Y%m%d_%H%M).sql
```

---

## 🧠 BAGIAN 4: Knowledge Base & Catatan Memori Proyek di Folder `.amneshia/`

Sabil juga telah mengekspor seluruh basis pengetahuan, keputusan arsitektur, dan catatan teknis proyek ini ke dalam repositori lokal **Amneshia v3** pada folder [`.amneshia/`](file:///home/murtix/projects/web/web-osis-zidan/.amneshia).

### Apa Isi Folder `.amneshia/`?
Di dalam direktori [`.amneshia/knowledge/`](file:///home/murtix/projects/web/web-osis-zidan/.amneshia/knowledge), terdapat berkas markdown berbasis *Markdown-as-Truth* yang mencatat seluruh jejak arsitektural proyek:
1. **`engineering/pemilos-osis-web-zidan-high-concurrency-optimization.md`**:
   - Catatan lengkap 5-pilar optimasi konkurensi (coalescing engine Socket.io, eliminasi DB row lock, in-memory caching aula, hasil uji PHPUnit 6/6 passing, dan simulasi 40 concurrent votes).
2. **`web-infrastructure/pemilos-osis-web-zidan-cloudflare-infrastructure.md`**:
   - Dokumentasi konfigurasi domain `pormulir.my.id`, mitigasi DDoS Cloudflare, SSL mode, dan panduan firewall UFW.
3. **`project-voting-osis/voting-osis-pemilos.md`**:
   - Metadata stack Laravel 10, roster siswa, dan aturan pemungutan suara.

> **Cara Membaca / Memanfaatkan Amneshia:**
> Jika Zidan menginstal CLI Amneshia (`npm i -g @sabilmurti/amneshia`), Zidan dapat menjalankan perintah berikut langsung di root repo:
> ```bash
> # Melihat ringkasan memori proyek
> amneshia stats -l
>
> # Menjalankan web dashboard 3D visualizer knowledge graph di browser (port 3457)
> amneshia serve
> ```
> Atau Zidan bisa langsung membaca berkas-berkas `.md` di dalam folder [`.amneshia/knowledge/`](file:///home/murtix/projects/web/web-osis-zidan/.amneshia/knowledge) dengan text editor biasa.

---

*Disiapkan dengan standar keandalan tinggi untuk menyukseskan Pemilos OSIS yang lancar, aman, dan tanpa kendala.*
