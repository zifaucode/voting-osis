<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use PDO;

class MigrateMysqlToSqlite extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:mysql-to-sqlite';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Migrasikan seluruh skema dan data dari MySQL ke SQLite dengan WAL mode aktif';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info("=== MEMULAI MIGRASI DATABASE MYSQL KE SQLITE (WAL MODE) ===");

        $sqlitePath = database_path('database.sqlite');
        if (!file_exists($sqlitePath)) {
            touch($sqlitePath);
        }

        // 1. Terapkan PRAGMA WAL & Pragmas Performa Tinggi pada berkas SQLite
        $pdo = new PDO("sqlite:" . $sqlitePath);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $pdo->exec("PRAGMA journal_mode = WAL;");
        $pdo->exec("PRAGMA synchronous = NORMAL;");
        $pdo->exec("PRAGMA busy_timeout = 5000;");
        $pdo->exec("PRAGMA cache_size = -64000;");
        $pdo->exec("PRAGMA temp_store = MEMORY;");
        $pdo->exec("PRAGMA foreign_keys = ON;");

        $journalMode = $pdo->query("PRAGMA journal_mode;")->fetchColumn();
        $this->info("✔ SQLite WAL Mode Terverifikasi: {$journalMode}");

        // 2. Jalankan Migrasi Skema ke SQLite
        $this->info("Menjalankan migrasi skema tabel ke SQLite...");
        Artisan::call('migrate', [
            '--database' => 'sqlite',
            '--force' => true,
        ]);
        $this->line(Artisan::output());

        // 3. Salin Data Tabel dari MySQL ke SQLite
        $tables = [
            'users',
            'web_settings',
            'osis_chairman_candidates',
            'total_vote_users',
        ];

        $mysql = DB::connection('mysql');
        $sqlite = DB::connection('sqlite');

        // Matikan sementara foreign keys saat import data
        $sqlite->statement('PRAGMA foreign_keys = OFF;');

        foreach ($tables as $table) {
            $this->info("Menyalin data tabel [{$table}]...");
            $rows = $mysql->table($table)->get()->map(function ($row) {
                return (array)$row;
            })->toArray();

            $sqlite->table($table)->truncate();

            if (!empty($rows)) {
                // Batch insert ke SQLite
                foreach (array_chunk($rows, 100) as $chunk) {
                    $sqlite->table($table)->insert($chunk);
                }
                $this->info("✔ {$table}: " . count($rows) . " baris data berhasil disalin.");
            } else {
                $this->comment("- {$table}: Kosong (0 baris).");
            }
        }

        $sqlite->statement('PRAGMA foreign_keys = ON;');

        // 4. Verifikasi Data SQLite
        $userCount = $sqlite->table('users')->count();
        $candidateCount = $sqlite->table('osis_chairman_candidates')->count();
        $settingCount = $sqlite->table('web_settings')->count();

        $this->newLine();
        $this->info("=== HASIL MIGRASI KE SQLITE ===");
        $this->table(
            ['Tabel', 'Jumlah Baris Data SQLite'],
            [
                ['users', $userCount],
                ['osis_chairman_candidates', $candidateCount],
                ['web_settings', $settingCount],
            ]
        );

        $this->info("✔ Migrasi selesai! SQLite WAL siap menggantikan MariaDB dengan kecepatan penuh!");
        return 0;
    }
}
