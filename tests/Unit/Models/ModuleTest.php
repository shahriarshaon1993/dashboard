<?php

declare(strict_types=1);

use App\Models\Module;

it('to array module', function (): void {
    $module = Module::factory()->create()->fresh();

    expect(array_keys($module->toArray()))
        ->toEqual([
            'id',
            'name',
            'description',
            'created_at',
            'updated_at',
        ]);
});
