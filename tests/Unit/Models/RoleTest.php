<?php

declare(strict_types=1);

use App\Models\Role;

test('to array role', function (): void {
    $role = Role::factory()->create()->fresh();

    expect(array_keys($role->toArray()))
        ->toEqual([
            'id',
            'name',
            'guard_name',
            'created_at',
            'updated_at',
        ]);
});
