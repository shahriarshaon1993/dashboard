<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\PermissionRegistrar;

final class ModuleAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        $modules = [
            'dashboard' => [
                'description' => 'Manage user access to application dashboard.',
                'permissions' => ['access dashboard'],
            ],
            'user' => [
                'description' => 'Manage users and their permissions.',
                'permissions' => ['view users', 'create users', 'update users', 'delete users', 'import users', 'export users'],
            ],
            'role' => [
                'description' => 'Manage roles and their permissions.',
                'permissions' => ['view roles', 'create roles', 'update roles', 'delete roles', 'import roles', 'export roles'],
            ],
            'General Setting' => [
                'description' => 'Manage General Setting and their permissions.',
                'permissions' => ['access settings'],
            ],
            'Activity Log' => [
                'description' => 'Manage Activity Log.',
                'permissions' => ['view activity', 'delete activity', 'export activity'],
            ],
        ];

        foreach ($modules as $moduleName => $moduleData) {
            $module = \App\Models\Module::create([
                'name' => $moduleName,
                'description' => $moduleData['description'],
            ]);

            foreach ($moduleData['permissions'] as $permissionName) {
                \Spatie\Permission\Models\Permission::create([
                    'name' => $permissionName,
                    'module_id' => $module->id,
                ]);
            }
        }
    }
}
