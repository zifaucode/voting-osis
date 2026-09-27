#!/usr/bin/env bash
# ==============================================================================
# SCRIPT BACKUP LIVE SQLITE NON-BLOCKING (ONLINE BACKUP API)
# ==============================================================================
# Dapat dijalankan via Cron setiap 15-30 menit selama pemilihan berlangsung
# Contoh crontab: */15 * * * * /var/www/pemilos/deployment/backup-pemilos.sh >> /var/log/pemilos-backup.log 2>&1

set -e

PROJECT_DIR="${1:-$(cd "$(dirname "${BASH_SOURCE[0]}")/.." && pwd)}"
DB_SRC="${PROJECT_DIR}/database/database.sqlite"
BACKUP_DIR="${PROJECT_DIR}/storage/backups"
TIMESTAMP=$(date +"%Y%m%d_%H%M%S")
BACKUP_FILE="${BACKUP_DIR}/pemilos_${TIMESTAMP}.sqlite"

mkdir -p "$BACKUP_DIR"

if [ -f "$DB_SRC" ]; then
    # Menggunakan SQLite Online Backup API bawaan PHP (Zero Downtime, Tidak Mengunci Siswa yang Sedang Memilih, Tanpa Dependensi CLI Eksternal)
    php -r '
        $srcPath = "'"$DB_SRC"'";
        $destPath = "'"$BACKUP_FILE"'";
        $src = new SQLite3($srcPath, SQLITE3_OPEN_READONLY);
        $dest = new SQLite3($destPath);
        $src->backup($dest);
        $src->close();
        $dest->close();
    '
    gzip -f "$BACKUP_FILE"

    echo "[$(date '+%Y-%m-%d %H:%M:%S')] ✔ Backup online berhasil: ${BACKUP_FILE}.gz ($(du -h ${BACKUP_FILE}.gz | cut -f1))"

    # Retensi lokal: Hapus cadangan yang lebih lama dari 7 hari
    find "$BACKUP_DIR" -type f -name "pemilos_*.sqlite.gz" -mtime +7 -delete
else
    echo "[$(date '+%Y-%m-%d %H:%M:%S')] ✖ Berkas database tidak ditemukan: $DB_SRC"
fi
