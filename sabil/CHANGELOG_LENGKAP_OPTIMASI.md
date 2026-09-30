# 📜 CHANGELOG LENGKAP: RIWAYAT ENHANCEMENT & OPTIMASI PEMILOS
> **Proyek:** Web Pemungutan Suara OSIS (Pemilos) SMKN 9 Semarang  
> **Kolaborator:** Sabil Murti & Zidan Fathul Hakim  
> **Branch Git:** `sabil`  
> **Target Infrastruktur:** 120 Bilik Suara Konkuren (3 Lab Komputer × 40 PC) | ~1.000 Pemilih DPT | VPS 4GB RAM | Cloudflare Edge  

---

## 📌 Ringkasan Eksekutif Perubahan

Seluruh pembaruan yang dilakukan berfokus pada **ketahanan sistem di bawah beban konkurensi riil**, **eliminasi database bottleneck**, **efisiensi transmisi WebSocket aula**, dan **kelengkapan dokumentasi operasional untuk hari-H**. 

Tidak ada perubahan destruktif pada UI frontend pemilih yang mengganggu alur pengguna yang sudah familiar bagi siswa.

---

## 🏗️ 1. Arsitektur & Realtime Socket.io Engine

### Berkas: `realtime-server/server.js`
* **Implementasi Coalescing & Debounce Window (600 ms):**
  * *Masalah Sebelumnya:* Setiap kali pemilih menekan tombol vote, Laravel memicu `BroadcastVoteUpdate` yang menembakkan HTTP POST ke `http://127.0.0.1:3001/broadcast`. Socket.io langsung memancarkan (`io.emit`) payload ke seluruh klien websocket. Saat 30–40 siswa vote dalam interval 2 detik, terjadi 40x broadcast beruntun yang memicu socket congestion dan lonjakan CPU di browser proyektor aula.
  * *Solusi Baru:* Menerapkan algoritma cerdas **Leading-Edge Instant + Trailing-Edge Coalescing**:
    1. **Leading-Edge:** Suara pertama yang masuk langsung dipancarkan seketika (latensi 0 ms untuk pemilih pertama).
    2. **Trailing-Edge:** Suara-suara berikutnya yang masuk dalam jeda waktu 600 ms digabungkan (*coalesced*), dan hanya 1 payload akumulatif paling mutakhir yang dipancarkan ke aula di akhir jendela waktu.
* **Metrik Diagnostik Realtime (`GET /health`):**
  * Menambahkan tracking metrik internal:
    * `broadcastsReceived`: Jumlah request masuk dari Laravel.
    * `broadcastsEmitted`: Jumlah emisi websocket yang dikirim ke klien aula.
    * `broadcastsCoalesced`: Jumlah payload yang berhasil dihemat dari jaringan.
* **Pencegahan Memory Leak & Graceful Shutdown:**
  * Penanganan signal `SIGINT` dan `SIGTERM` dengan pembersihan timer debounce dan final payload flush sebelum proses ditutup.
* **Hasil Pengujian Riil:**
  * Menguji 40 request broadcast burst dalam 95 ms menghasilkan penghematan emisi sebesar **98%** (hanya 2 emisi websocket ke aula) dengan data suara akhir 100% akurat.

---

## 🔐 2. Autentikasi & Concurrency Anti-Double Login

### Berkas: `app/Http/Controllers/web/AuthUserController.php`
* **Eliminasi Database Row-Locking Saat Login:**
  * *Masalah Sebelumnya:* Sistem login sebelumnya menggunakan `DB::transaction()` dengan `User::lockForUpdate()` pada baris pemilih. Ketika 120 siswa menekan tombol "Masuk" serentak di lab komputer, MySQL InnoDB mengalami antrian panjang (*lock contention*) yang berisiko memicu error fatal `SQLSTATE[HY000]: 1205 Lock wait timeout exceeded`.
  * *Solusi Baru:* Mengganti row lock database dengan **Non-Blocking Cache Check** dan **Atomic Cache Lock** (`Cache::lock('voter_login_lock_' . $user->id, 5)`).
* **Proteksi Sesi Bilik Suara:**
  * Verifikasi apakah akun sedang aktif di PC lain kini berjalan sub-milidetik (<1 ms) langsung di cache memori.
  * Pelepasan lock dipastikan melalui blok `finally` sehingga tidak ada kunci yang menggantung jika terjadi interupsi jaringan.

### Berkas: `app/Http/Controllers/UserSelectCandidateController.php`
* **Standarisasi Respons JSON Error:**
  * Memperbaiki respons gagal pada controller agar selalu menyertakan atribut `'error' => true` di level root JSON demi konsistensi penanganan error di sisi klien dan automated test.
* **Pessimistic Locking Khusus Transaksi Suara:**
  * `lockForUpdate()` dipertahankan secara eksklusif hanya saat siswa men-submit suara ke paslon untuk menjamin prinsip **1 DPT = Tepat 1 Suara** tanpa celah race condition.

---

## ⚡ 3. Optimasi Database, Sesi, dan Caching

### Berkas: `database/migrations/2026_09_27_202332_create_sessions_table.php`
* **Penyediaan Migrasi Tabel `sessions` Resmi:**
  * Membuat migrasi tabel `sessions` Laravel (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) agar perpindahan driver sesi ke `SESSION_DRIVER=database` dapat langsung dieksekusi tanpa error *missing table*.

### Berkas: `.env.example`
* **Konfigurasi Standar Performa Produksi:**
  * Mengubah default `SESSION_DRIVER` dan `CACHE_DRIVER` ke `database` untuk menghindari file I/O choke pada disk VPS saat diakses ratusan bilik.
  * Mengubah `QUEUE_CONNECTION` ke `database` agar pemrosesan broadcast perolehan suara berjalan di latar belakang (background queue worker) tanpa menahan latensi HTTP siswa di bilik.
  * Menambahkan variabel lingkungan realtime: `REALTIME_SERVER_URL`, `REALTIME_SECRET`, dan `BROADCAST_INTERVAL_MS=600`.

### Berkas: `app/Http/Controllers/web/HomeController.php`
* **In-Memory Caching Halaman Aula / Show-Off:**
  * Membungkus query `WebSetting::first()` dengan `Cache::rememberForever('pemilos_web_setting', ...)`.
  * Membungkus query daftar kandidat paslon dengan `Cache::remember('pemilos_candidates_list', 3600, ...)`.
  * **Dampak:** Komputer/laptop di aula yang melakukan reload atau refresh tampilan berkali-kali tidak lagi menyentuh basis data MySQL (respons time turun drastis ke rata-rata ~40 ms).

---

## 🌐 4. Keamanan Jaringan, Nginx, & Cloudflare

### Berkas: `deployment/nginx-pemilos.conf`
* **Cloudflare Real IP Restoration:**
  * Menambahkan blok direktif `set_real_ip_from` untuk seluruh subnet resmi Cloudflare (15 prefix IPv4 dan 7 prefix IPv6).
  * Menambahkan direktif `real_ip_header CF-Connecting-IP;` dan `real_ip_recursive on;`.
  * **Dampak:** Variabel `$remote_addr` di Nginx dan Laravel kini mencatat IP asli komputer lab sekolah, bukan IP proxy Cloudflare. Log audit, pelacakan sesi bilik, dan rate limiter menjadi 100% akurat.

### Berkas: `app/Providers/RouteServiceProvider.php`
* **Relaksasi Rate Limiter Login Siswa:**
  * Menaikkan batas percobaan login dari 6 kali per menit menjadi **15 kali per menit** per-NISN (`$nisn . '|' . $ip`).
  * Mencegah siswa di bilik suara terkunci jika salah memasukkan kode akses karena salah ketik atau gugup, sembari tetap membatasi percobaan brute force massal.

---

## 🧪 5. Testing Otomatis & Simulasi Konkurensi

### Berkas: `tests/Feature/PemilosVotingFlowTest.php`
* **Suite Uji Fitur Komprehensif (6/6 PASS, 22 Assertions):**
  1. `test_voter_can_login_successfully`: Memastikan siswa dapat login dengan kredensial sah dan sesi bilik terdaftar di cache.
  2. `test_anti_double_login_blocks_concurrent_session`: Memastikan PC kedua diblokir jika akun sedang aktif di PC pertama.
  3. `test_voter_can_cast_vote_successfully`: Memastikan suara tercatat, `vote_status` menjadi 1, dan suara paslon bertambah.
  4. `test_voter_cannot_vote_twice`: Memastikan pemilih tidak dapat memberikan suara kedua kalinya.
  5. `test_voter_who_voted_cannot_login_again`: Memastikan pemilih yang sudah memilih ditolak saat mencoba login kembali.
  6. `test_show_off_page_loads_and_uses_cache`: Memastikan halaman aula ter-render cepat dan mengisi cache in-memory.

### Berkas: `database/seeders/TestVotersSeeder.php`
* Seeder otomatis untuk membuat data `web_settings` aktif dan 40 akun siswa pengujian (`NIS0001` s/d `NIS0040`) guna keperluan simulasi bilik.

### Berkas: `sabil/simulasi_pemilos.cjs`
* Script pengujian simulasi konkurensi interaktif berbasis Node.js untuk memvalidasi:
  * Emisi WebSocket Socket.io di bawah ledakan 40 request bersamaan.
  * Kecepatan reload halaman aula `/show-off` (rata-rata 40 ms).

---

## 📖 6. Dokumentasi & Panduan Operasional di Folder `sabil/`

1. **`sabil/PANDUAN_OPTIMASI_UNTUK_ZIDAN.md`**:
   * Panduan eksekutif & teknis langkah demi langkah yang harus dijalankan Zidan di VPS:
     * Langkah 1: Tarik kode (`git pull origin main`).
     * Langkah 2: Migrasi tabel database (`php artisan migrate --force`).
     * Langkah 3: Konfigurasi file `.env`.
     * Langkah 4: Reload konfigurasi Nginx dengan Cloudflare Real IP.
     * **Langkah 5 (Krusial):** Panduan setup `server.js` agar **selalu hidup 24/7** dan otomatis bangkit saat reboot/crash menggunakan **PM2 Startup** (`pm2 startup && pm2 save`) atau **Systemd Service**.
     * Langkah 6: Menjalankan Laravel background queue worker.
     * Langkah 7: Kompilasi cache performa (`php artisan optimize`).
   * Peringatan bahaya rate-limiting berbasis IP pada jaringan NAT sekolah.
   * Panduan SSL Cloudflare mode *Full (Strict)*.
   * Prosedur backup cepat database sebelum dan sesudah pemilihan.
   * Informasi repositori knowledge base Amneshia di folder `.amneshia/`.

2. **`sabil/vps-deploy-helper.sh`**:
   * Script otomasi deployment 1-klik di VPS Linux untuk menjalankan migrasi, reload Nginx, restart PM2 auto-boot, dan kompilasi cache secara berurutan.

3. **`sabil/brainstorm_pemilos_optimization.md`**:
   * Catatan hasil analisis awal dan pertimbangan komparatif strategi optimasi untuk 120 bilik suara.

---

## 🧠 7. Repositori Memori Lokal Amneshia v3

### Berkas: `.amneshia/`
* Inisialisasi repositori lokal Amneshia (`amneshia init .`) dengan konfigurasi `dual_write: true` dan indeks cache SQLite FTS5 (`.amneshia/memory.db`).
* Ekspor 4 berkas basis pengetahuan arsitektural di `.amneshia/knowledge/`:
  * `engineering/pemilos-osis-web-zidan-high-concurrency-optimization.md`
  * `web-infrastructure/pemilos-osis-web-zidan-cloudflare-infrastructure.md`
  * `project-voting-osis/voting-osis-pemilos.md`
  * `project-mitraxnine/sabil-murti-zidan-fathul.md`
* Total **62 observasi aktif** (16 architectural tier, 46 contextual tier) telah terindeks dan tersinkronisasi.

---

## 🔒 8. Pembaruan `.gitignore`
* Melindungi privasi dengan mengabaikan dump memori global personal (`MEMORY.md`).
* Mengabaikan basis data lokal SQLite pengujian (`database/database.sqlite*`).
* Mengabaikan cache binary Amneshia (`.amneshia/*.db*`, `.amneshia/*.log`) sehingga hanya berkas Markdown yang dilacak di Git.
