#!/usr/bin/env bash
set -e

echo "=== 1. Menyiapkan Sertifikat SSL Sementara (Self-Signed) ==="
mkdir -p /etc/ssl/localcerts
if [ ! -f /etc/ssl/localcerts/selfsigned.crt ]; then
    openssl req -x509 -nodes -days 365 -newkey rsa:2048 \
        -keyout /etc/ssl/localcerts/selfsigned.key \
        -out /etc/ssl/localcerts/selfsigned.crt \
        -subj "/CN=pormulir.my.id"
fi

echo "=== 2. Memasang Konfigurasi Nginx untuk Port 80 & 443 ==="
cat << 'EOF' > /etc/nginx/sites-available/pemilos
# ==============================================================================
# PEMILOS ENTERPRISE NGINX CONFIGURATION (OPTIMIZED FOR 4GB RAM VPS)
# ==============================================================================

server {
    listen 80;
    listen [::]:80;
    server_name pormulir.my.id www.pormulir.my.id pemilos.smkn9semarang.sch.id 202.10.45.34 localhost;

    root /var/www/pemilos/public;
    index index.php index.html index.htm;

    charset utf-8;
    client_max_body_size 12M;

    # Let's Encrypt ACME challenge (harus sebelum blokir dotfiles)
    location ^~ /.well-known/acme-challenge/ {
        allow all;
        root /var/www/pemilos/public;
        default_type "text/plain";
        try_files $uri =404;
    }

    # Security Headers
    add_header X-Frame-Options "SAMEORIGIN" always;
    add_header X-Content-Type-Options "nosniff" always;
    add_header X-XSS-Protection "1; mode=block" always;
    add_header Referrer-Policy "strict-origin-when-cross-origin" always;

    # WebSocket Reverse Proxy (Socket.io ke port 3001)
    location /socket.io/ {
        proxy_pass http://127.0.0.1:3001;
        proxy_http_version 1.1;
        proxy_set_header Upgrade $http_upgrade;
        proxy_set_header Connection "upgrade";
        proxy_set_header Host $host;
        proxy_set_header X-Real-IP $remote_addr;
        proxy_set_header X-Forwarded-For $proxy_add_x_forwarded_for;
        proxy_set_header X-Forwarded-Proto $scheme;
        proxy_cache_bypass $http_upgrade;
        proxy_read_timeout 86400s;
        proxy_send_timeout 86400s;
    }

    # FastCGI Buffer Tuning untuk PHP-FPM
    location ~ \.php$ {
        fastcgi_pass unix:/run/php/php8.2-fpm.sock;
        fastcgi_index index.php;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;

        fastcgi_buffer_size 32k;
        fastcgi_buffers 16 16k;
        fastcgi_busy_buffers_size 64k;
        fastcgi_temp_file_write_size 64k;
        fastcgi_connect_timeout 30s;
        fastcgi_send_timeout 60s;
        fastcgi_read_timeout 60s;
    }

    # Cache Agresif untuk Static Assets
    location ~* \.(jpg|jpeg|png|gif|ico|webp|svg|css|js|woff|woff2|ttf|eot)$ {
        expires 7d;
        add_header Cache-Control "public, no-transform, immutable";
        access_log off;
        log_not_found off;
        try_files $uri =404;
    }

    # Blokir berkas sensitif & kredensial
    location ~* \.(env|git|sql|xlsx|xls|csv|bak|log|sh)$ {
        deny all;
        return 404;
    }

    location ~ /\. {
        deny all;
        access_log off;
        log_not_found off;
    }

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    error_page 404 /index.php;
}

server {
    listen 443 ssl http2;
    listen [::]:443 ssl http2;
    server_name pormulir.my.id www.pormulir.my.id pemilos.smkn9semarang.sch.id 202.10.45.34 localhost;

    ssl_certificate /etc/ssl/localcerts/selfsigned.crt;
    ssl_certificate_key /etc/ssl/localcerts/selfsigned.key;
    ssl_protocols TLSv1.2 TLSv1.3;
    ssl_ciphers HIGH:!aNULL:!MD5;

    root /var/www/pemilos/public;
    index index.php index.html index.htm;

    charset utf-8;
    client_max_body_size 12M;

    # Let's Encrypt ACME challenge
    location ^~ /.well-known/acme-challenge/ {
        allow all;
        root /var/www/pemilos/public;
        default_type "text/plain";
        try_files $uri =404;
    }

    # Security Headers
    add_header X-Frame-Options "SAMEORIGIN" always;
    add_header X-Content-Type-Options "nosniff" always;
    add_header X-XSS-Protection "1; mode=block" always;
    add_header Referrer-Policy "strict-origin-when-cross-origin" always;

    # WebSocket Reverse Proxy (Socket.io ke port 3001)
    location /socket.io/ {
        proxy_pass http://127.0.0.1:3001;
        proxy_http_version 1.1;
        proxy_set_header Upgrade $http_upgrade;
        proxy_set_header Connection "upgrade";
        proxy_set_header Host $host;
        proxy_set_header X-Real-IP $remote_addr;
        proxy_set_header X-Forwarded-For $proxy_add_x_forwarded_for;
        proxy_set_header X-Forwarded-Proto $scheme;
        proxy_cache_bypass $http_upgrade;
        proxy_read_timeout 86400s;
        proxy_send_timeout 86400s;
    }

    # FastCGI Buffer Tuning untuk PHP-FPM
    location ~ \.php$ {
        fastcgi_pass unix:/run/php/php8.2-fpm.sock;
        fastcgi_index index.php;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;

        fastcgi_buffer_size 32k;
        fastcgi_buffers 16 16k;
        fastcgi_busy_buffers_size 64k;
        fastcgi_temp_file_write_size 64k;
        fastcgi_connect_timeout 30s;
        fastcgi_send_timeout 60s;
        fastcgi_read_timeout 60s;
    }

    # Cache Agresif untuk Static Assets
    location ~* \.(jpg|jpeg|png|gif|ico|webp|svg|css|js|woff|woff2|ttf|eot)$ {
        expires 7d;
        add_header Cache-Control "public, no-transform, immutable";
        access_log off;
        log_not_found off;
        try_files $uri =404;
    }

    # Blokir berkas sensitif & kredensial
    location ~* \.(env|git|sql|xlsx|xls|csv|bak|log|sh)$ {
        deny all;
        return 404;
    }

    location ~ /\. {
        deny all;
        access_log off;
        log_not_found off;
    }

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    error_page 404 /index.php;
}
EOF

ln -sf /etc/nginx/sites-available/pemilos /etc/nginx/sites-enabled/pemilos
nginx -t
systemctl reload nginx
echo "=== Nginx berhasil di-reload dengan SSL sementara! ==="
