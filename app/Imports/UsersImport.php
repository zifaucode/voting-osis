<?php

namespace App\Imports;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;

class UsersImport implements ToModel, WithStartRow
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function startRow(): int
    {
        return 2;
    }

    public function model(array $row)
    {
        return new User([
            'nisn' => $row[1],
            'name' => $row[2],
            'class' => $row[3],
            'code_access' => $row[4],
            'role' => 2,
            'password' => Hash::make(12345678),
        ]);
    }
}
