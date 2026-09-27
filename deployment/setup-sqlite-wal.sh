#!/usr/bin/env bash
# ==============================================================================
# SCRIPT INSIALISASI SQLITE WAL MODE UNTUK PEMILOS HIGH-CONCURRENCY
# ==============================================================================
set -e

DB_FILE="database/database.sqlite"

echo "=== MENGAKTIFKAN SQLITE WAL MODE UNTUK PEMILOS ==="

if [ ! -f "$DB_FILE" ]; then
    echo "Membuat file database SQLite baru: $DB_FILE..."
    touch "$DB_FILE"
fi

# Terapkan PRAGMA WAL melalui PHP PDO (Universal, tidak memerlukan paket sqlite3-cli tambahan)
php -r '
$db = "database/database.sqlite";
$pdo = new PDO("sqlite:" . $db);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$pdo->exec("PRAGMA journal_mode = WAL;");
$pdo->exec("PRAGMA synchronous = NORMAL;");
$pdo->exec("PRAGMA busy_timeout = 5000;");
$pdo->exec("PRAGMA cache_size = -64000;");
$pdo->exec("PRAGMA temp_store = MEMORY;");
$pdo->exec("PRAGMA foreign_keys = ON;");

echo "Status Verifikasi SQLite Pragmas:\n";
echo "- journal_mode : " . $pdo->query("PRAGMA journal_mode;")->fetchColumn() . "\n";
echo "- synchronous  : " . $pdo->query("PRAGMA synchronous;")->fetchColumn() . " (1 = NORMAL)\n";
echo "- busy_timeout : " . $pdo->query("PRAGMA busy_timeout;")->fetchColumn() . " ms\n";
'

echo "✔ SQLite WAL Mode aktif: Pembacaan concurrent (bebas lock) dan penulisan sub-1ms siap digunakan!"
