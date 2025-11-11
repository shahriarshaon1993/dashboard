<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\GeneralSetting;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<GeneralSetting>
 */
final class GeneralSettingFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'site_title' => 'Dashboard',
            'date_format' => 'd M Y',
            'timezone' => 'UTC',
            'developed_by' => fake()->name(),
        ];
    }
}
