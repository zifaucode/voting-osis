<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class ImportStudentsRoster extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'pemilos:import-students {--source= : Path to JSON dataset file}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Impor data daftar siswa pemilih (NIS, Nama, Kelas, Kode Akses) ke dalam tabel users';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $sourcePath = $this->option('source') ?: base_path('database/data/students_ready_for_db.json');

        if (!file_exists($sourcePath)) {
            // Check scratch path fallback
            $fallback = '/home/zidan/.gemini/antigravity-ide/brain/893d8b07-52f7-4eb4-8350-f18ee38b8743/scratch/students_ready_for_db.json';
            if (file_exists($fallback)) {
                $sourcePath = $fallback;
            } else {
                $this->error("File dataset siswa tidak ditemukan pada: {$sourcePath}");
                return 1;
            }
        }

        $students = json_decode(file_get_contents($sourcePath), true);
        if (empty($students)) {
            $this->error("Dataset siswa kosong atau tidak valid.");
            return 1;
        }

        $totalCount = count($students);
        $this->info("=== MEMULAI IMPOR DATA PEMILIH SISWA ({$totalCount} SISWA) ===");

        // Pre-compute single bcrypt hash for 12345678 for instant batch insertion
        $hashedPassword = Hash::make('12345678');
        $now = now()->toDateTimeString();

        $inserted = 0;
        $updated = 0;

        $bar = $this->output->createProgressBar($totalCount);
        $bar->start();

        DB::beginTransaction();
        try {
            foreach (array_chunk($students, 50) as $chunk) {
                foreach ($chunk as $s) {
                    $nis = trim((string)$s['nis']);
                    $name = trim((string)$s['name']);
                    $class = trim((string)$s['class']);
                    $code = trim((string)$s['code_access']);
                    $hashedCode = str_starts_with($code, '$2y$') ? $code : Hash::make($code);

                    $existing = User::where('nisn', $nis)->first();
                    if ($existing) {
                        $existing->update([
                            'name' => $name,
                            'class' => $class,
                            'code_access' => $hashedCode,
                            'role' => 2,
                            'updated_at' => $now,
                        ]);
                        $updated++;
                    } else {
                        User::create([
                            'nisn' => $nis,
                            'name' => $name,
                            'class' => $class,
                            'code_access' => $hashedCode,
                            'role' => 2,
                            'vote_status' => 0,
                            'is_vote' => null,
                            'password' => $hashedPassword,
                            'created_at' => $now,
                            'updated_at' => $now,
                        ]);
                        $inserted++;
                    }

                    $bar->advance();
                }
            }

            DB::commit();
            $bar->finish();
            $this->newLine(2);

            $this->info("✅ Berhasil memproses data siswa:");
            $this->line("   - Siswa Baru Ditambahkan : <comment>{$inserted}</comment>");
            $this->line("   - Siswa Diperbarui       : <comment>{$updated}</comment>");
            $this->line("   - Total Siswa Aktif      : <comment>" . User::where('role', 2)->where('class', 'not like', '%GURU%')->where('class', 'not like', '%KARYAWAN%')->count() . "</comment>");
            $this->line("   - Total Seluruh Pemilih  : <comment>" . User::where('role', 2)->count() . "</comment> (termasuk Guru & Karyawan)");

            return 0;
        } catch (\Throwable $e) {
            DB::rollBack();
            $this->newLine();
            $this->error("Gagal melakukan impor: " . $e->getMessage());
            return 1;
        }
    }
}
