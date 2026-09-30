<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'role'    => 1,
            'username'    => 'admin',
            'name'    => 'ADMIN',
            'password'    => Hash::make('Pemilos9#AdminSecure2026!'),
        ]);
    }
}
