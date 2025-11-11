<?php

declare(strict_types=1);

use App\Imports\RoleImport;
use App\Models\Role;

it('can creates role from row data', function (): void {
    $row = [0 => 'dummy', 1 => 'admin', 2 => 'web'];

    $import = new RoleImport();
    $role = $import->model($row);

    expect($role)->toBeInstanceOf(Role::class)
        ->name->toBe('admin')
        ->guard_name->toBe('web');
});
