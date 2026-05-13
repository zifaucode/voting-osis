<?php

namespace Database\Seeders;

use App\Models\OsisChairmanCandidate;
use Illuminate\Database\Seeder;

class DefaultCandidateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $candidates = [
            [
                'name' => 'Batman',
                'vice_name' => 'Robin',
                'sequence_number' => '1',
                'class' => 'XI MIPA 1',
                'visi' => 'Menjadikan sekolah sebagai tempat yang aman dan disiplin.',
                'misi' => '1. Membasmi pelanggaran tata tertib di sekolah. 2. Mengadakan patroli keamanan.',
                'image' => '../../gambar/default-candidate/batman.jpg',
                'vote_total' => 0,
            ],
            [
                'name' => 'Superman',
                'vice_name' => 'Supergirl',
                'sequence_number' => '2',
                'class' => 'XI MIPA 2',
                'visi' => 'Membawa harapan dan keadilan bagi seluruh siswa.',
                'misi' => '1. Mendorong siswa terbang lebih tinggi mencapai prestasi. 2. Membantu sesama siswa dengan kekuatan super.',
                'image' => '../../gambar/default-candidate/superman.jpg',
                'vote_total' => 0,
            ],
            [
                'name' => 'Joker',
                'vice_name' => 'Harley Quinn',
                'sequence_number' => '3',
                'class' => 'XI IPS 1',
                'visi' => 'Why so serious? Menjadikan sekolah tempat yang penuh tawa dan kreativitas tanpa batas.',
                'misi' => '1. Mengadakan pentas seni komedi rutin. 2. Membawa senyum dan kebebasan berekspresi di setiap wajah siswa.',
                'image' => '../../gambar/default-candidate/joker.jpg',
                'vote_total' => 0,
            ],
        ];

        foreach ($candidates as $candidate) {
            OsisChairmanCandidate::create($candidate);
        }
    }
}
