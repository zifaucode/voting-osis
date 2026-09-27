#!/usr/bin/env bash
# ==============================================================================
# PEMILOS ONE-CLICK PRODUCTION DEPLOYMENT & SYNC SCRIPT
# ==============================================================================
# Script ini mengotomatiskan update kode, dependency composer, migrasi SQLite,
# cache warming Laravel, WebSocket realtime PM2, dan reload service web server.
#
# Penggunaan di VPS:
# bash deployment/deploy-to-vps.sh
# ==============================================================================

set -e

APP_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")/.." && pwd)"
cd "$APP_DIR"

echo "=========================================================================="
echo ">> [1/8] Memulai Deployment Pemilos di: $APP_DIR"
echo "=========================================================================="

# 1. Update Dependensi PHP via Composer
echo ">> [2/8] Mengoptimalkan dependensi Composer..."
if [ -f "composer.json" ]; then
    composer install --no-interaction --prefer-dist --optimize-autoloader --no-dev
fi

# 2. Inisialisasi & Verifikasi SQLite WAL
echo ">> [3/8] Memverifikasi Database SQLite WAL Mode..."
bash "$APP_DIR/deployment/setup-sqlite-wal.sh"

# 3. Jalankan Migrasi Database
echo ">> [4/8] Menjalankan migrasi skema database..."
php artisan migrate --force

# 4. Optimasi Foto & Aset Kandidat ke WebP
echo ">> [5/8] Memeriksa & mengonversi aset gambar kandidat ke format WebP..."
php artisan images:optimize-webp --update-db

# 5. Laravel Production Cache Warming (OPcache Friendly)
echo ">> [6/8] Menghangatkan cache produksi (Config, Route, View)..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# 6. Install Dependensi Node.js Realtime Server
if [ -d "$APP_DIR/realtime-server" ]; then
    echo ">> [7/8] Menyiapkan Node.js WebSocket Realtime Server..."
    (cd "$APP_DIR/realtime-server" && npm install --production --no-audit)
fi

# 7. Restart / Reload Daemon PM2 & Web Services
echo ">> [8/8] Memperbarui daemon background (PM2, PHP-FPM, Nginx)..."
if command -v pm2 > /dev/null 2>&1; then
    pm2 reload "$APP_DIR/deployment/ecosystem.config.cjs" || pm2 start "$APP_DIR/deployment/ecosystem.config.cjs"
    pm2 save
    echo "✔ PM2 daemons (realtime socket & queue worker) reloaded."
else
    echo "⚠ PM2 belum terpasang. Install via: npm install -g pm2"
fi

# Reload PHP-FPM jika tersedia
if systemctl is-active --quiet php8.4-fpm; then
    echo "  - Memuat ulang PHP 8.4-FPM..."
    sudo systemctl reload php8.4-fpm || true
elif systemctl is-active --quiet php8.3-fpm; then
    echo "  - Memuat ulang PHP 8.3-FPM..."
    sudo systemctl reload php8.3-fpm || true
elif systemctl is-active --quiet php8.2-fpm; then
    echo "  - Memuat ulang PHP 8.2-FPM..."
    sudo systemctl reload php8.2-fpm || true
fi

# Reload Nginx jika tersedia
if systemctl is-active --quiet nginx; then
    echo "  - Memuat ulang Nginx..."
    sudo nginx -t && sudo systemctl reload nginx || true
fi

echo ""
echo "=========================================================================="
echo "✔ DEPLOYMENT SELESAI DENGAN SUKSES!"
echo "  - Database: SQLite WAL High-Concurrency Active"
echo "  - Cache: Config, Route, and View Optimized"
echo "  - Images: WebP Bandwidth-Optimized"
echo "  - Realtime: PM2 Socket Server (Port 3001) & Queue Worker Online"
echo "=========================================================================="
