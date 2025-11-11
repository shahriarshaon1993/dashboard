<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Media;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Media>
 */
final class MediaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'model_type' => \App\Models\User::class,
            'model_id' => 1,
            'collection' => 'default',
            'name' => $this->faker->word(),
            'file_name' => $this->faker->uuid().'.jpg',
            'mime_type' => 'image/jpeg',
            'size' => $this->faker->numberBetween(10000, 500000),
        ];
    }
}
