<?php

declare(strict_types=1);

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\GeneralSetting;
use Illuminate\Database\Seeder;

final class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        GeneralSetting::factory()->create();

        $this->call([
            RoleSeeder::class,
            AdminSeeder::class,
            ModuleAndPermissionsSeeder::class,
        ]);
    }
}
