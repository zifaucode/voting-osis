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
        if (empty($row[1]) && empty($row[2])) {
            return null;
        }

        $nisn = trim((string)$row[1]);
        $name = trim((string)$row[2]);
        $class = trim((string)($row[3] ?? ''));
        $codeAccess = trim((string)($row[4] ?? ''));

        if ($nisn === '' || in_array(strtoupper($nisn), ['USERNAME', 'NISN', 'NIS'])) {
            return null;
        }

        $existing = User::where('nisn', $nisn)->first();
        if ($existing) {
            $existing->update([
                'name' => $name,
                'class' => $class ?: $existing->class,
                'code_access' => $codeAccess ?: $existing->code_access,
            ]);
            return null;
        }

        return new User([
            'nisn' => $nisn,
            'name' => $name,
            'class' => $class,
            'code_access' => $codeAccess,
            'role' => 2,
            'vote_status' => 0,
            'password' => Hash::make('12345678'),
        ]);
    }
}
