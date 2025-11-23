<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

final class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = User::query()->create([
            'name' => 'John Doe',
            'email' => 'admin@example.com',
            'phone' => fake()->phoneNumber(),
            'password' => Hash::make('password'),
        ]);

        $admin->assignRole('admin');

        $employee = User::query()->create([
            'name' => 'Employee',
            'email' => 'employee@example.com',
            'phone' => fake()->phoneNumber(),
            'password' => Hash::make('password'),
        ]);

        $employee->assignRole('employee');

        $customer = User::query()->create([
            'name' => 'Customer',
            'email' => 'customer@example.com',
            'phone' => fake()->phoneNumber(),
            'password' => Hash::make('password'),
        ]);

        $customer->assignRole('customer');
    }
}
