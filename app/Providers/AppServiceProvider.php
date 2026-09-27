<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Otomatis terapkan PRAGMA performa tinggi setiap kali koneksi SQLite dibuat
        \Illuminate\Support\Facades\Event::listen(\Illuminate\Database\Events\ConnectionEstablished::class, function ($event) {
            if ($event->connection instanceof \Illuminate\Database\SQLiteConnection) {
                $pdo = $event->connection->getPdo();
                $pdo->exec('PRAGMA journal_mode = WAL;');
                $pdo->exec('PRAGMA busy_timeout = 5000;');
                $pdo->exec('PRAGMA synchronous = NORMAL;');
                $pdo->exec('PRAGMA cache_size = -64000;');
                $pdo->exec('PRAGMA temp_store = MEMORY;');
            }
        });
    }
}
