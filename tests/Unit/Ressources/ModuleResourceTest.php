<?php

declare(strict_types=1);

use App\Http\Resources\ModuleResource;
use App\Models\Module;

it('module resource transforms correctly', function (): void {
    $module = Module::factory()->create(['name' => 'Test Module']);

    $result = (new ModuleResource($module))->toArray(request());

    expect($result)
        ->toBeArray()
        ->toHaveKeys(['id', 'name', 'description', 'permissions', 'created_at', 'updated_at'])
        ->and($result['id'])->toBe($module->id)
        ->and($result['name'])->toBe('Test Module');
});
