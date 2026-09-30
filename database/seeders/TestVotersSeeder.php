<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\WebSetting;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class TestVotersSeeder extends Seeder
{
    /**
     * Seed voters for testing and simulation.
     */
    public function run(): void
    {
        // 1. Buat WebSetting
        $setting = WebSetting::firstOrNew();
        $setting->web_title = 'Pemilihan Ketua OSIS SMKN 9 Semarang';
        $setting->school_name = 'SMKN 9 Semarang';
        $setting->year_period = '2026';
        $setting->start_date = date('Y-m-d');
        $setting->start_time = '00:00:00';
        $setting->end_time = '23:59:59';
        $setting->status = 'dibuka';
        $setting->save();

        // 2. Buat 40 pemilih testing (merefleksikan 1 lab komputer)
        for ($i = 1; $i <= 40; $i++) {
            $nisn = 'NIS' . str_pad($i, 4, '0', STR_PAD_LEFT);
            User::updateOrCreate(
                ['nisn' => $nisn],
                [
                    'name' => 'Siswa Test ' . $i,
                    'role' => 2,
                    'password' => Hash::make('secret123'),
                    'code_access' => Hash::make('PASS' . str_pad($i, 4, '0', STR_PAD_LEFT)),
                    'vote_status' => 0,
                    'is_vote' => null,
                ]
            );
        }
    }
}
