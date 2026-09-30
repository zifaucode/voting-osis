<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class HashVoterTokens extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'pemilos:hash-voter-tokens {--force : Jalankan tanpa konfirmasi}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Amankan dan hash seluruh kode akses/token pemilih di database menggunakan algoritma Bcrypt';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info("=== PENGAMANAN & HASHING KODE AKSES PEMILIH ===");

        // Cari pemilih (role = 2) yang kode aksesnya belum di-hash (tidak diawali $2y$)
        $unhashedUsers = User::where('role', 2)
            ->whereNotNull('code_access')
            ->where('code_access', '!=', '')
            ->where('code_access', 'not like', '$2y$%')
            ->get();

        $count = $unhashedUsers->count();

        if ($count === 0) {
            $this->info("✅ Semua token/kode akses pemilih sudah dalam kondisi ter-hash aman (Bcrypt). Tidak ada data plain-text yang perlu diubah.");
            return 0;
        }

        $this->line("Ditemukan <comment>{$count}</comment> pemilih dengan kode akses plain-text.");
        $this->line("Memulai enkripsi satu arah (Bcrypt) untuk mencegah kebocoran data jika database diretas...");

        $bar = $this->output->createProgressBar($count);
        $bar->start();

        $updated = 0;

        foreach ($unhashedUsers->chunk(100) as $chunk) {
            DB::transaction(function () use ($chunk, &$updated, $bar) {
                foreach ($chunk as $user) {
                    $plainCode = (string)$user->code_access;
                    
                    // Pastikan bukan hash yang terlewat
                    if (!str_starts_with($plainCode, '$2y$')) {
                        $user->code_access = Hash::make($plainCode);
                        $user->save();
                        $updated++;
                    }

                    $bar->advance();
                }
            });
        }

        $bar->finish();
        $this->newLine(2);

        $this->info("✅ Berhasil mengamankan {$updated} kode akses pemilih.");
        $this->line("   - Seluruh token di tabel `users` kini terenkripsi satu arah (Bcrypt).");
        $this->line("   - Login pemilih tetap berjalan normal via Hash::check().");
        $this->line("   - Data fisik untuk pencetakan kartu tetap aman tersimpan di berkas Excel master.");

        return 0;
    }
}
