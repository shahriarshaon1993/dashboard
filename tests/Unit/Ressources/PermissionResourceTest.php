<?php

declare(strict_types=1);

use App\Http\Resources\PermissionResource;
use App\Models\Permission;
use Illuminate\Http\Request;

it('transforms spatie permission with module relation correctly', function (): void {
    $permission = Permission::factory()->create([
        'name' => 'Editor',
        'guard_name' => 'Web',
    ]);

    $data = (new PermissionResource($permission))
        ->toArray(new Request());

    expect($data)
        ->toHaveKeys(['id', 'name', 'guard_name', 'module', 'created_at', 'updated_at'])
        ->and($data['name'])->toBe('Editor')
        ->and($data['guard_name'])->toBe('Web');
});
