<?php

namespace App\Console\Commands;

use App\Models\OsisChairmanCandidate;
use App\Models\WebSetting;
use Illuminate\Console\Command;

class OptimizeImagesWebp extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'images:optimize-webp {--quality=80 : Kualitas WebP (1-100)} {--update-db : Otomatis perbarui nama berkas di database}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Konversi seluruh foto kandidat dan aset gambar ke format WebP untuk menghemat 80-90% bandwidth bilik suara';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $quality = (int)$this->option('quality');
        $updateDb = $this->option('update-db');

        $this->info("=== MEMULAI OPTIMASI GAMBAR KE WEBP (Kualitas: {$quality}%) ===");

        $imageDir = public_path('file/image');
        if (!is_dir($imageDir)) {
            $this->error("Direktori {$imageDir} tidak ditemukan.");
            return 1;
        }

        $files = scandir($imageDir);
        $totalOriginal = 0;
        $totalOptimized = 0;
        $convertedCount = 0;

        foreach ($files as $file) {
            if (in_array($file, ['.', '..', '.gitkeep'])) continue;

            $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
            if (!in_array($ext, ['png', 'jpg', 'jpeg'])) continue;

            $sourcePath = $imageDir . '/' . $file;
            $webpName = pathinfo($file, PATHINFO_FILENAME) . '.webp';
            $targetPath = $imageDir . '/' . $webpName;

            $origSize = filesize($sourcePath);
            $totalOriginal += $origSize;

            $imageData = @file_get_contents($sourcePath);
            if (!$imageData) continue;

            $srcImage = @imagecreatefromstring($imageData);
            if (!$srcImage) {
                $this->warn("Gagal membaca gambar: {$file}");
                continue;
            }

            // Pertahankan transparansi PNG jika ada
            imagepalettetotruecolor($srcImage);
            imagealphablending($srcImage, true);
            imagesavealpha($srcImage, true);

            // Simpan ke WebP
            imagewebp($srcImage, $targetPath, $quality);
            imagedestroy($srcImage);

            $newSize = filesize($targetPath);
            $totalOptimized += $newSize;
            $savingPct = round((1 - ($newSize / $origSize)) * 100, 1);

            $this->line("✔ {$file} (" . round($origSize / 1024, 1) . " KB) -> {$webpName} (" . round($newSize / 1024, 1) . " KB) [Hemat {$savingPct}%]");
            $convertedCount++;

            // Update nama file gambar paslon di DB jika diminta
            if ($updateDb) {
                OsisChairmanCandidate::where('image', $file)->update(['image' => $webpName]);
            }
        }

        if ($updateDb) {
            \Illuminate\Support\Facades\Cache::forget('pemilos_candidates_list');
            \Illuminate\Support\Facades\Cache::forget('live_votes_api_payload');
            $this->info("✔ Data kandidat di database telah diperbarui ke berkas .webp dan cache telah dibersihkan.");
        }

        $this->newLine();
        $this->info("=== HASIL OPTIMASI WEBP ===");
        $totalSaved = max(0, $totalOriginal - $totalOptimized);
        $totalPct = $totalOriginal > 0 ? round(($totalSaved / $totalOriginal) * 100, 1) : 0;

        $this->table(
            ['Metrik', 'Nilai'],
            [
                ['Jumlah Berkas Dikonversi', $convertedCount],
                ['Ukuran Asli Total', round($totalOriginal / 1024, 1) . ' KB (' . round($totalOriginal / 1048576, 2) . ' MB)'],
                ['Ukuran WebP Baru Total', round($totalOptimized / 1024, 1) . ' KB (' . round($totalOptimized / 1048576, 2) . ' MB)'],
                ['Penghematan Bandwidth', round($totalSaved / 1024, 1) . ' KB (' . $totalPct . '%)'],
            ]
        );

        return 0;
    }
}
