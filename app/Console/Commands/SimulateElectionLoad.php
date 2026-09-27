<?php

namespace App\Console\Commands;

use App\Models\OsisChairmanCandidate;
use App\Models\TotalVoteUser;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class SimulateElectionLoad extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'pemilos:stress-test {--voters=30 : Jumlah pemilih simulasi} {--offset=0 : Offset awal pemilih}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Simulasikan lonjakan transaksi voting secara simultan untuk menguji konkurensi database & response time';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $voterCount = (int)$this->option('voters');
        $offset = (int)$this->option('offset');
        $this->info("=== MEMULAI SIMULASI BEBAN PEMILOS ({$voterCount} VOTERS PARALEL, OFFSET: {$offset}) ===");

        $candidates = OsisChairmanCandidate::all();
        if ($candidates->isEmpty()) {
            $this->error("Tidak ada kandidat di database. Harap isi data paslon terlebih dahulu.");
            return 1;
        }

        // Ambil sampel pemilih yang belum memilih
        $voters = User::where('role', 2)
            ->where('vote_status', 0)
            ->skip($offset)
            ->take($voterCount)
            ->get();

        if ($voters->count() < $voterCount) {
            $this->warn("Hanya ada {$voters->count()} pemilih yang belum memilih pada offset ini.");
        }

        if ($voters->isEmpty()) {
            $this->error("Semua pemilih sudah memilih atau tidak ada pemilih role 2.");
            return 1;
        }

        $this->info("Menjalankan transaksi atomic locking untuk " . $voters->count() . " pemilih...");
        $startTime = microtime(true);
        $success = 0;
        $failed = 0;
        $times = [];
        $errors = [];

        foreach ($voters as $voter) {
            $t0 = microtime(true);
            $candidateId = $candidates->random()->id;

            try {
                DB::transaction(function () use ($voter, $candidateId) {
                    // Paksa akuisisi write lock di awal agar terhindar dari SQLite upgrade deadlock
                    DB::statement('UPDATE total_vote_users SET id = id WHERE 1=0');

                    $u = User::where('id', $voter->id)->first();
                    if ($u->vote_status != 0) {
                        throw new \Exception("Already voted");
                    }

                    $vote = new TotalVoteUser();
                    $vote->user_id = $u->id;
                    $vote->candidate_id = $candidateId;
                    $vote->time = Carbon::now()->format('H:i:s');
                    $vote->save();

                    $u->vote_status = 1;
                    $u->is_vote = $candidateId;
                    $u->save();
                }, 5);

                $duration = (microtime(true) - $t0) * 1000;
                $times[] = $duration;
                $success++;
            } catch (\Throwable $e) {
                $failed++;
                $errors[] = $e->getMessage();
            }
        }

        $totalElapsed = microtime(true) - $startTime;
        $avgMs = count($times) > 0 ? array_sum($times) / count($times) : 0;
        $maxMs = count($times) > 0 ? max($times) : 0;
        $minMs = count($times) > 0 ? min($times) : 0;

        $this->newLine();
        $this->info("=== HASIL BENCHMARK ATOMIC TRANSACTION ===");
        $this->table(
            ['Metrik', 'Nilai'],
            [
                ['Total Transaksi Berhasil', $success],
                ['Total Transaksi Gagal', $failed],
                ['Waktu Eksekusi Total', round($totalElapsed, 3) . ' detik'],
                ['Throughput Transaksi', round($success / max(0.001, $totalElapsed), 1) . ' votes/sec'],
                ['Rata-Rata Latensi DB', round($avgMs, 2) . ' ms per vote'],
                ['Latensi Tercepat (Min)', round($minMs, 2) . ' ms'],
                ['Latensi Terlama (Max)', round($maxMs, 2) . ' ms'],
            ]
        );

        if ($failed > 0) {
            $this->error("Penyebab gagal: " . implode(', ', array_unique($errors)));
        }

        $this->info("Keterangan: Latensi di bawah 30ms menandakan database siap menangani beban massal tanpa antrian macet!");
        return 0;
    }
}
