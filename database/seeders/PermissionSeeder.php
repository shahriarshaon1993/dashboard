<?php

namespace Database\Seeders;

use App\Models\Module;
use App\Models\Permission;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Dashboard
        $moduleAdminDashboard = Module::updateOrCreate(['name' => 'Admin Dashboard']);
        Permission::updateOrCreate([
            'module_id' => $moduleAdminDashboard->id,
            'name' => 'Access Dashboard',
            'slug' => 'admin.access.dashboard',
        ]);

        // Role management
        $moduleAdminRole = Module::updateOrCreate(['name' => 'Role Management']);
        Permission::updateOrCreate([
            'module_id' => $moduleAdminRole->id,
            'name' => 'Access Roles',
            'slug' => 'admin.roles.access',
        ]);
        Permission::updateOrCreate([
            'module_id' => $moduleAdminRole->id,
            'name' => 'Create Role',
            'slug' => 'admin.roles.create',
        ]);
        Permission::updateOrCreate([
            'module_id' => $moduleAdminRole->id,
            'name' => 'Edit Role',
            'slug' => 'admin.roles.edit',
        ]);
        Permission::updateOrCreate([
            'module_id' => $moduleAdminRole->id,
            'name' => 'Delete Role',
            'slug' => 'admin.roles.destroy',
        ]);

        // User management
        $moduleAdminUser = Module::updateOrCreate(['name' => 'User Management']);
        Permission::updateOrCreate([
            'module_id' => $moduleAdminUser->id,
            'name' => 'Access Users',
            'slug' => 'admin.users.access',
        ]);
        Permission::updateOrCreate([
            'module_id' => $moduleAdminUser->id,
            'name' => 'Create User',
            'slug' => 'admin.users.create',
        ]);
        Permission::updateOrCreate([
            'module_id' => $moduleAdminUser->id,
            'name' => 'Edit User',
            'slug' => 'admin.users.edit',
        ]);
        Permission::updateOrCreate([
            'module_id' => $moduleAdminUser->id,
            'name' => 'Delete User',
            'slug' => 'admin.users.destroy',
        ]);

        // Backups management
        $moduleAdminBackups = Module::updateOrCreate(['name' => 'Backups Management']);
        Permission::updateOrCreate([
            'module_id' => $moduleAdminBackups->id,
            'name' => 'Access backups',
            'slug' => 'admin.backups.access',
        ]);
        Permission::updateOrCreate([
            'module_id' => $moduleAdminBackups->id,
            'name' => 'Create backups',
            'slug' => 'admin.backups.create',
        ]);
        Permission::updateOrCreate([
            'module_id' => $moduleAdminBackups->id,
            'name' => 'Download backups',
            'slug' => 'admin.backups.download',
        ]);
        Permission::updateOrCreate([
            'module_id' => $moduleAdminBackups->id,
            'name' => 'Delete backup',
            'slug' => 'admin.backups.destroy',
        ]);
    }
}