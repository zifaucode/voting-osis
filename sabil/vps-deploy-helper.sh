#!/usr/bin/env bash
# ==============================================================================
# PEMILOS VPS DEPLOYMENT & OPTIMIZATION HELPER
# Dibuat oleh Sabil untuk memudahkan Zidan menerapkan optimasi di VPS dalam 1 klik
# ==============================================================================

set -e

PROJECT_DIR="/var/www/pemilos"
if [ ! -d "$PROJECT_DIR" ]; then
    PROJECT_DIR="$(pwd)"
fi

echo "============================================================"
echo "🚀 PEMILOS VPS AUTOMATIC DEPLOYMENT & OPTIMIZER"
echo "Target Directory: $PROJECT_DIR"
echo "============================================================"

# 1. Masuk ke direktori project
cd "$PROJECT_DIR"

# 2. Pastikan file .env ada
if [ ! -f ".env" ]; then
    echo "❌ Error: File .env tidak ditemukan di $PROJECT_DIR"
    exit 1
fi

echo "[1/6] Menjalankan migrasi tabel sessions database..."
php artisan migrate --force

echo "[2/6] Memperbarui dependensi realtime Socket.io..."
if [ -d "realtime-server" ]; then
    cd realtime-server
    npm install --production --silent
    cd "$PROJECT_DIR"
fi

echo "[3/6] Memeriksa & Memperbarui konfigurasi Nginx..."
if [ -f "/etc/nginx/sites-available/pemilos" ] && [ -f "$PROJECT_DIR/deployment/nginx-pemilos.conf" ]; then
    sudo cp "$PROJECT_DIR/deployment/nginx-pemilos.conf" /etc/nginx/sites-available/pemilos
    sudo nginx -t && sudo systemctl reload nginx
    echo "✅ Nginx berhasil direload dengan konfigurasi Cloudflare Real IP!"
else
    echo "⚠️ File Nginx sites-available/pemilos tidak ditemukan atau bukan Linux VPS root. Lewati reload Nginx."
fi

echo "[4/6] Mengaktifkan & Mengunci service Socket.io realtime di PM2 (Auto-boot)..."
if command -v pm2 &> /dev/null; then
    pm2 restart pemilos-realtime || (cd "$PROJECT_DIR/realtime-server" && pm2 start server.js --name "pemilos-realtime")
    pm2 save
    echo "✅ Realtime Socket.io broadcaster aktif & disimpan di PM2 auto-start."
else
    echo "⚠️ PM2 tidak terdeteksi. Pastikan server.js berjalan via systemd (lihat panduan di sabil/PANDUAN_OPTIMASI_UNTUK_ZIDAN.md)."
fi

echo "[5/6] Mengompilasi cache performa tinggi Laravel..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "[6/6] Menjalankan uji diagnostik internal..."
if curl -s http://127.0.0.1:3001/health | grep -q "ok"; then
    echo "✅ Health check Realtime Broadcaster: ONLINE & SEHAT!"
else
    echo "⚠️ Peringatan: Realtime Broadcaster di port 3001 belum merespon."
fi

echo "============================================================"
echo "🎉 DEPLOYMENT & ENHANCEMENT SELESAI!"
echo "Semua optimasi untuk 120 bilik suara telah aktif."
echo "============================================================"
