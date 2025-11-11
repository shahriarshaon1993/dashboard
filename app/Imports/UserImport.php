<?php

declare(strict_types=1);

namespace App\Imports;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;

final class UserImport implements ToModel
{
    /**
     * @param  array<int, mixed>  $row
     */
    public function model(array $row): User
    {
        return new User([
            'name' => $row[1] ?? null,
            'email' => $row[2] ?? null,
            'phone' => $row[3] ?? null,
            'password' => Hash::make('password'),
            'is_active' => $row[6] === 'Active' ? 'active' : 'inactive',
        ]);
    }
}
