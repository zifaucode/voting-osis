# Pemilos Realtime Broadcaster (Node.js + Socket.io)

Microservice WebSocket berkecepatan tinggi (< 20ms) dan hemat memori (~30MB RAM) yang menghubungkan pencatatan suara di Laravel dengan layar proyektor / dashboard admin secara instan tanpa polling database.

---

## 1. Arsitektur Komunikasi

1. Siswa mencoblos di bilik suara -> `POST /user-choose` (Laravel).
2. Laravel memvalidasi, mengunci baris data dengan `lockForUpdate()`, dan menyimpan ke MySQL dalam `DB::transaction()`.
3. Setelah sukses commit, Laravel (`RealtimeBroadcaster::broadcast()`) menembak HTTP POST lokal ke `http://127.0.0.1:3001/broadcast`.
4. Microservice Node.js langsung memancarkan event `voteUpdate` ke semua browser klien (Layar Proyektor Aula `/live-count` & Dashboard Admin `/admin`) via WebSocket Socket.io secara instan (<20 milidetik).
5. **Smart Fallback:** Jika server Node.js belum aktif atau koneksi socket terputus, browser klien otomatis beralih ke *Smart HTTP Polling* (5 detik) tanpa error.

---

## 2. Cara Menjalankan di Lingkungan Lokal (Development)

Di terminal:
```bash
cd realtime-server
node server.js
```
Akan muncul output:
```
====================================================
🚀 Pemilos Realtime Broadcaster running on :3001
📡 WebSocket ready | Health: http://localhost:3001/health
====================================================
```

---

## 3. Cara Menjalankan di Server Produksi (VPS KVM)

### Opsi A: Menggunakan PM2 (Rekomendasi Utama)

Install PM2 secara global jika belum ada:
```bash
npm install -g pm2
```

Jalankan service:
```bash
cd /path/ke/pemilos/realtime-server
pm2 start ecosystem.config.cjs
pm2 save
pm2 startup
```

Status dan log:
```bash
pm2 status
pm2 logs pemilos-realtime
```

---

### Opsi B: Menggunakan Systemd Linux Service

Buat file `/etc/systemd/system/pemilos-realtime.service`:
```ini
[Unit]
Description=Pemilos Realtime Broadcaster
After=network.target

[Service]
Type=simple
User=www-data
WorkingDirectory=/var/www/pemilos/realtime-server
ExecStart=/usr/bin/node server.js
Restart=always
RestartSec=10
Environment=PORT=3001
Environment=REALTIME_SECRET=pemilos_secret_broadcast_key_2026

[Install]
WantedBy=multi-user.target
```

Aktifkan service:
```bash
sudo systemctl daemon-reload
sudo systemctl enable pemilos-realtime
sudo systemctl start pemilos-realtime
```

---

## 4. Konfigurasi Nginx Reverse Proxy (Agar Port 3001 Tidak Perlu Dibuka ke Publik)

Tambahkan blok berikut di file konfigurasi Nginx situs Anda (`/etc/nginx/sites-available/pemilos`):

```nginx
# Proxy untuk WebSocket Socket.io
location /socket.io/ {
    proxy_pass http://127.0.0.1:3001;
    proxy_http_version 1.1;
    proxy_set_header Upgrade $http_upgrade;
    proxy_set_header Connection "upgrade";
    proxy_set_header Host $host;
    proxy_set_header X-Real-IP $remote_addr;
    proxy_set_header X-Forwarded-For $proxy_add_x_forwarded_for;
    proxy_cache_bypass $http_upgrade;
}
```

Dengan konfigurasi ini, klien cukup mengakses via port 80/443 SSL standar tanpa perlu membuka port 3001 di firewall/UFW.
