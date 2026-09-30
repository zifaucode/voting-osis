---
id: "a0b51efa-6c65-4958-9b4e-f1c530594787"
name: "Pemilos OSIS Web (Zidan) & Cloudflare Infrastructure"
type: "project"
domain: "web-infrastructure"
visibility: "public"
allowed_agents: []
created: "2026-09-30T09:54:34.691Z"
updated: "2026-09-30T09:54:34.691Z"
---
## Observations

- **[architectural]** ### 1. Context & Rationale
- Project Context: Web voting aplikasi pemilihan OSIS (Pemilos) milik rekan user (Zidan).
- Problem: Sebelumnya web hanya menggunakan raw IP public VPS (202.10.45.34) tanpa domain, port 443 (SSL) belum aktif, dan rentan terhadap script spam/flood/DDoS saat traffic spike pemungutan suara serentak.
- Solution: Memasang domain sementara pormulir.my.id melalui Cloudflare Proxy untuk menyerap DDoS L3/L4/L7, mengaktifkan SSL Edge, menyaring automated bot/script, serta menyembunyikan origin IP VPS.
  `id: 9772c7ce-496d-4409-b966-2a0e2dc35174 | confidence: 1 | status: active`

- **[architectural]** ### 2. Technical Implementation & Architecture
- Domain & DNS: Domain pormulir.my.id didelegasikan ke Cloudflare DNS. Record A apex (@) diarahkan ke origin IP 202.10.45.34 dengan status Proxied (Orange Cloud).
- SSL/TLS Edge Mode: Dikonfigurasi ke mode 'Flexible' pada Cloudflare (Edge client-to-Cloudflare terenkripsi HTTPS/TLS 1.3, Cloudflare-to-origin berkomunikasi via HTTP port 80 karena VPS belum memiliki sertifikat SSL/certbot).
- Origin Stack: Web server Nginx 1.18.0 (Ubuntu) menjalankan Laravel framework dengan PHP-FPM dan MySQL.
- Application Layer: Laravel membutuhkan APP_URL=https://pormulir.my.id, session cookie secure, serta TrustProxies ($proxies = '*') agar tidak terjadi HTTP-to-HTTPS redirect loop 301/302.
- Nginx Virtual Host: Server name disetel mencakup pormulir.my.id www.pormulir.my.id 202.10.45.34 dengan modul real_ip (set_real_ip_from range IP Cloudflare dan real_ip_header CF-Connecting-IP) agar logging dan rate limit mencatat IP asli siswa, bukan proxy IP Cloudflare.
  `id: 0f9a9c1b-16cf-4644-bc3d-d38bee35939f | confidence: 1 | status: active`

- **[architectural]** ### 3. Operational Parameters
- Origin IPv4: 202.10.45.34
- Active Web Ports: 80 (HTTP plaintext origin). Port 443 belum aktif di origin server.
- Public Frontend URL: https://pormulir.my.id/ (Redirect default ke /user/login).
- Cloudflare Security Settings:
  - SSL/TLS: Flexible, Always Use HTTPS = ON, Automatic HTTPS Rewrites = ON.
  - Bot Mitigation: Bot Fight Mode = ON.
  - WAF Rate Limiting: 10 req / 10s pada path /user/login dan URI yang mengandung 'vote' dengan action Managed Challenge.
  - Geo-blocking: Filter traffic di luar Indonesia jika diperlukan saat hari pemungutan suara.
  `id: c695dac4-7892-4e50-b7f3-981ed36e6e63 | confidence: 1 | status: active`

- **[architectural]** ### 4. Verification & Test Outcomes
- Connectivity Check: Direct ping ke origin 202.10.45.34 responsif (13-14ms, 0% packet loss).
- HTTP Status Check: Raw origin mengembalikan HTTP/1.1 302 Found ke http://202.10.45.34/user/login beserta cookie XSRF-TOKEN dan voting_osis_session.
- Cloudflare Edge Verification: curl -sI https://pormulir.my.id/ menghasilkan HTTP/2 302 dengan SSL Cloudflare aktif (cf-ray, secure cookie, HSTS, cf-cache-status: DYNAMIC) dan redirect mulus ke https://pormulir.my.id/user/login tanpa loop 521.
  `id: 4404c04d-cd08-4758-a14a-baa030e748b9 | confidence: 1 | status: active`

- **[architectural]** ### 5. Architectural Guardrails & Gotchas
- Error 521 Prevention: Jangan ubah SSL mode Cloudflare ke 'Full' atau 'Full (Strict)' selama port 443 di VPS 202.10.45.34 belum diinstall sertifikat SSL valid / Let's Encrypt.
- Direct IP Bypass Hazard: Attacker yang mengetahui IP 202.10.45.34 masih bisa membypass Cloudflare WAF jika port 80 VPS terbuka bebas. Zidan harus mengonfigurasi UFW di VPS untuk hanya mengizinkan port 80 & 443 dari range IP resmi Cloudflare (https://www.cloudflare.com/ips-v4 dan /ips-v6).
- High Traffic Capacity: Pada hari H Pemilos, PHP-FPM pm.max_children di VPS Zidan harus dinaikkan (30-50 worker) dan MySQL max_connections disesuaikan agar tidak memicu 502 Bad Gateway saat ratusan request voting masuk simultan. Cloudflare 'Under Attack Mode' disiapkan sebagai kill-switch jika terjadi insiden flood script.
  `id: 52421e06-7779-47e4-be64-8cbb68f194d3 | confidence: 1 | status: active`

- **[architectural]** ### 1. Context & Rationale (Update Post-Sync Zidan)
- Sync Status: Berhasil git pull commit 2da4514 dari origin/main (EmperiumX/voting-osis), 18 files changed, 8,671 insertions.
- Intent: Zidan mengimplementasikan pengamanan UFW Cloudflare-only, SSL Let's Encrypt dengan redirect 301 ke pormulir.my.id, hashing token pemilih satu arah (Bcrypt), proteksi anti-double login per-bilik suara (single-device session lock via cache), rate limiting login admin/voter, dan impor 892 roster siswa.
- Audit Goal: Sabil Murti mengarahkan optimasi & security hardening tingkat enterprise untuk mencegah race condition, lock-wait bottleneck, direct-origin bypass, dan rate limit evasion.
  `id: a82db796-7545-45d7-a929-6396da177edc | confidence: 1 | status: active`

- **[architectural]** ### 2. Technical Implementation Details
- UFW Firewall (deployment/lockdown-cloudflare-ufw.sh): Port 22 SSH tetap terbuka, port 80 & 443 diizinkan secara eksklusif hanya dari rentang IP resmi Cloudflare (curl https://www.cloudflare.com/ips-v4 dan /ips-v6), default deny incoming.
- Nginx SSL (deployment/nginx-pemilos.conf & setup-ssl-nginx.sh): Konfigurasi vhost port 80 redirect 301 ke https://pormulir.my.id, port 443 dengan SSL cert Let's Encrypt / self-signed, HTTP/2, buffer PHP-FPM, static asset caching 7d, dan blokir berkas sensitif (.env, .git, .sql, .sh).
- Anti-Double Login (AuthUserController.php, HomeController.php, UserSelectCandidateController.php): Menggunakan Cache lock active_voter_{id} dan active_voter_time_{id} (TTL 5 menit) untuk memvalidasi bahwa hanya satu session ID yang aktif per pemilih. Suara dicatat dengan DB transaction lockForUpdate().
- Token Protection (HashVoterTokens.php & UsersImport.php): Token plain text di-hash menggunakan Bcrypt (Hash::make), login diverifikasi via Hash::check(), tampilan admin disamarkan dengan badge 'Terhash (Aman)'.
- Route Rate Limiting (RouteServiceProvider.php): admin-login dibatasi 5 req/min per username+IP; voter-login dibatasi 6 req/min per nisn+IP.
  `id: f0e68783-1b21-42df-8425-0ddd54b4b49b | confidence: 1 | status: active`

- **[architectural]** ### 3. Operational Parameters & Infrastructure Metrics
- Active Domains & Ports: https://pormulir.my.id/ (Port 443 HTTPS SSL via Cloudflare Proxy). Port 80 HTTP auto-redirect 301.
- Server Target: VPS Ubuntu IP 202.10.45.34 (Nginx 1.18 + PHP 8.2-FPM + MySQL + Node.js Socket.io on port 3001).
- Total Roster: 892 data siswa/pemilih terdaftar di database/data/students_ready_for_db.json.
- Cloudflare Setup: Mode SSL harus 'Full' atau 'Full (Strict)' setelah Let's Encrypt aktif di origin, agar tidak terjadi HTTP 301 infinite redirect loop.
  `id: d12293a2-91de-41a9-b5ce-a24e9ee0907f | confidence: 1 | status: active`

- **[architectural]** ### 4. Verification & Audit Findings
- Codebase Graph: Ter-indexing di codebase-memory-mcp dengan 666 nodes, 1,160 edges.
- Security Gap 1 (Nginx Real IP): nginx-pemilos.conf belum memuat direktif set_real_ip_from Cloudflare IP ranges dan real_ip_header CF-Connecting-IP. Akibatnya $remote_addr di log Nginx dan rate limiting level webserver masih membaca IP proxy Cloudflare, bukan IP pemilih asli.
- Security Gap 2 (Unthrottled Voting Endpoint): Route POST /user-choose (UserSelectCandidateController::store) belum memiliki middleware throttle/rate limiter di routes/web.php. Pengguna terotentikasi dapat membanjiri koneksi DB transaction lock wait.
- Performance Gap 1 (showOff Query Caching): Method HomeController::showOff melakukan WebSetting::first() dan OsisChairmanCandidate::all() langsung ke database tanpa cache (berbeda dengan index() yang menggunakan Cache::rememberForever).
- Concurrency Hazard (Cache Driver): Default .env.example masih CACHE_DRIVER=file dan SESSION_DRIVER=file. Di bawah konkurensi ratusan bilik suara simultan, file-based cache/session dapat mengalami filesystem lock contention.
  `id: 275e97d6-56f1-4ae2-8b19-6187e6a5cfb0 | confidence: 1 | status: active`

- **[architectural]** ### 5. Architectural Guardrails & Concrete Recommendations
- 1. Tambahkan Cloudflare Real IP di nginx-pemilos.conf menggunakan set_real_ip_from dan real_ip_header CF-Connecting-IP agar log & IP tracing presisi.
- 2. Tambahkan rate limiter throttle:vote-post di POST /user-choose (misal 5-10 req/menit per user/IP) untuk melindungi pool worker PHP-FPM dan lock transaksi database.
- 3. Cache data di HomeController::showOff menggunakan Cache::remember agar endpoint public display tidak membebani query MySQL.
- 4. Pastikan di VPS production CACHE_DRIVER menggunakan database (dengan tabel cache yang sudah dimigrasi) atau Redis, bukan file.
- 5. Pastikan SSL mode di Cloudflare Dashboard sudah diubah dari 'Flexible' ke 'Full' / 'Full (Strict)' setelah script SSL Let's Encrypt dijalankan di VPS agar redirect 301 port 80 tidak memicu ERR_TOO_MANY_REDIRECTS.
  `id: 4ca79710-b471-479b-923b-2636fface42c | confidence: 1 | status: active`

## Relations

_No relations recorded yet._
