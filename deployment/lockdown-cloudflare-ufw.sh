#!/usr/bin/env bash
set -e

echo "=== 1. Memastikan Port SSH (22) Tetap Terbuka ==="
ufw allow 22/tcp comment 'SSH Port'

echo "=== 2. Mendaftarkan Seluruh IP Resmi Cloudflare (Port 80 & 443) ==="
for ip in $(curl -s https://www.cloudflare.com/ips-v4); do
    if [ -n "$ip" ]; then
        ufw allow proto tcp from "$ip" to any port 80,443 comment 'Cloudflare IPv4'
    fi
done

for ip in $(curl -s https://www.cloudflare.com/ips-v6); do
    if [ -n "$ip" ]; then
        ufw allow proto tcp from "$ip" to any port 80,443 comment 'Cloudflare IPv6'
    fi
done

echo "=== 3. Menghapus Aturan Lama yang Mengizinkan Akses Web Publik (Anywhere) ==="
# Hapus aturan allow publik Anywhere agar traffic non-Cloudflare diblokir total
ufw delete allow 80/tcp || true
ufw delete allow 443/tcp || true
ufw delete allow 80 || true
ufw delete allow 443 || true

echo "=== 4. Mengaktifkan Kebijakan Default Deny Incoming ==="
ufw default deny incoming
ufw default allow outgoing
echo "y" | ufw enable
ufw reload

echo "=== 5. Status UFW Terkini ==="
ufw status verbose
