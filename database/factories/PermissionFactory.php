<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Module;
use App\Models\Permission;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Permission>
 */
final class PermissionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->sentence(2),
            'guard_name' => 'web',
            'module_id' => Module::factory()->create(),
        ];
    }
}
