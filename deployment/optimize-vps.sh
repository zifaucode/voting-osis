#!/usr/bin/env bash
# ==============================================================================
# PEMILOS VPS LINUX KERNEL & SYSTEM OPTIMIZATION SCRIPT (4GB RAM)
# ==============================================================================
# Script ini mengonfigurasi kernel Linux, tuning memori swap, network backlog,
# dan perizinan direktori untuk menangani lonjakan konkurensi pemilih massal.
#
# Jalankan di VPS dengan hak akses root / sudo:
# sudo bash deployment/optimize-vps.sh
# ==============================================================================

set -e

echo "=== MEMULAI OPTIMASI VPS PEMILOS (ENTERPRISE HIGH-CONCURRENCY) ==="

# 1. Pastikan dieksekusi dengan hak akses root / sudo
if [ "$EUID" -ne 0 ]; then
  echo "❌ Error: Harap jalankan script ini sebagai root atau dengan sudo:"
  echo "   sudo bash deployment/optimize-vps.sh"
  exit 1
fi

# 2. Kernel & Virtual Memory Tuning via sysctl
echo ">> [1/5] Mengonfigurasi parameter Kernel Linux & Virtual Memory..."
SYSCTL_CONF="/etc/sysctl.d/99-pemilos.conf"

cat << 'EOF' > "$SYSCTL_CONF"
# Pemilos High-Concurrency Kernel Tuning (4GB RAM)
# Swappiness rendah agar Linux memaksimalkan RAM fisik dan tidak agresif swap ke disk
vm.swappiness = 20
vm.vfs_cache_pressure = 50

# Network socket backlog untuk menampung lonjakan ribuan request serentak
net.core.somaxconn = 4096
net.ipv4.tcp_max_syn_backlog = 4096
net.ipv4.ip_local_port_range = 1024 65535
net.ipv4.tcp_tw_reuse = 1
net.ipv4.tcp_fin_timeout = 15

# File descriptor limits untuk ribuan koneksi WebSocket & Nginx
fs.file-max = 2097152
EOF

sysctl -p "$SYSCTL_CONF" > /dev/null
echo "✔ Kernel sysctl berhasil dimuat dan aktif."

# 3. File Descriptor Limits (limits.conf)
echo ">> [2/5] Mengonfigurasi open file descriptor limits (nofile)..."
LIMITS_CONF="/etc/security/limits.d/99-pemilos.conf"

cat << 'EOF' > "$LIMITS_CONF"
* soft nofile 65535
* hard nofile 65535
root soft nofile 65535
root hard nofile 65535
www-data soft nofile 65535
www-data hard nofile 65535
EOF
echo "✔ Batas file descriptor 65535 berhasil diterapkan untuk semua user dan daemon."

# 4. Pembersihan Service Bloat
echo ">> [3/5] Memeriksa dan mematikan background service yang tidak diperlukan..."
if systemctl is-active --quiet fwupd; then
  echo "  - Menonaktifkan fwupd (Firmware Update Daemon) penghemat RAM..."
  systemctl stop fwupd || true
  systemctl disable fwupd || true
fi

# 5. Swap File Verification (Minimal 2GB Swap)
echo ">> [4/5] Memeriksa ketersediaan Swap Memory..."
SWAP_TOTAL=$(free -m | awk '/^Swap:/ {print $2}')
if [ "$SWAP_TOTAL" -lt 1024 ]; then
  echo "  - Swap terdeteksi kecil ($SWAP_TOTAL MB). Membuat Swapfile 2GB sebagai safety net..."
  if [ ! -f /swapfile ]; then
    fallocate -l 2G /swapfile || dd if=/dev/zero of=/swapfile bs=1M count=2048
    chmod 600 /swapfile
    mkswap /swapfile
    swapon /swapfile
    if ! grep -q '/swapfile' /etc/fstab; then
      echo '/swapfile none swap sw 0 0' >> /etc/fstab
    fi
    echo "✔ Swapfile 2GB berhasil diaktifkan."
  fi
else
  echo "✔ Swap memory mencukupi (${SWAP_TOTAL} MB)."
fi

# 6. Perizinan Direktori Web & SQLite WAL
echo ">> [5/5] Mengonfigurasi kepemilikan dan perizinan direktori Pemilos..."
SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")/.." && pwd)"

# Berikan www-data kepemilikan storage, bootstrap/cache, dan direktori database
chown -R www-data:www-data "$SCRIPT_DIR/storage" "$SCRIPT_DIR/bootstrap/cache" "$SCRIPT_DIR/database"
chmod -R 775 "$SCRIPT_DIR/storage" "$SCRIPT_DIR/bootstrap/cache" "$SCRIPT_DIR/database"

# SQLite memerlukan permission rw pada file DB dan direktori database agar bisa membuat file WAL dan SHM
if [ -f "$SCRIPT_DIR/database/database.sqlite" ]; then
  chmod 664 "$SCRIPT_DIR/database/database.sqlite"
fi

# Buat konfigurasi Logrotate untuk mencegah storage penuh oleh log
LOGROTATE_CONF="/etc/logrotate.d/pemilos"
cat << EOF > "$LOGROTATE_CONF"
$SCRIPT_DIR/storage/logs/*.log {
    daily
    missingok
    rotate 14
    compress
    delaycompress
    notifempty
    create 0664 www-data www-data
}
EOF
echo "✔ Logrotate otomatis aktif di $LOGROTATE_CONF."

echo ""
echo "=========================================================================="
echo "✔ OPTIMASI VPS PEMILOS BERHASIL DITERAPKAN!"
echo "  - RAM Swappiness: 20 (Physical RAM Priority)"
echo "  - VFS Cache: 50 (Inodes & SQLite database cache diprioritaskan di RAM)"
echo "  - Socket Backlog: 4096 (Tahan antrian login massal)"
echo "  - File Descriptors: 65,535 (WebSocket & Nginx concurrency siap)"
echo "  - Direktori & Database SQLite WAL: Read/Write siap untuk www-data"
echo "=========================================================================="
