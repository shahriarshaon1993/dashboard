<?php

declare(strict_types=1);

namespace App\Imports;

use App\Models\Role;
use Maatwebsite\Excel\Concerns\ToModel;

final class RoleImport implements ToModel
{
    /**
     * @param  array<int, string>  $row
     */
    public function model(array $row): Role
    {
        return new Role([
            'name' => $row[1],
            'guard_name' => $row[2],
        ]);
    }
}
