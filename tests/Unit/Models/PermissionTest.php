<?php

declare(strict_types=1);

use App\Models\Module;
use App\Models\Permission;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

test('to array permission', function (): void {
    $permission = Permission::factory()->create()->fresh();

    expect(array_keys($permission->toArray()))
        ->toEqual([
            'id',
            'module_id',
            'name',
            'guard_name',
            'created_at',
            'updated_at',
        ]);
});

test('permission belong to module', function (): void {
    $permission = Permission::factory()->create()->fresh();

    $relation = $permission->module();

    expect($relation)->toBeInstanceOf(BelongsTo::class);
    expect($relation->getRelated())->toBeInstanceOf(Module::class);
});
