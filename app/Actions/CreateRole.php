<?php

declare(strict_types=1);

namespace App\Actions;

use App\DTOs\RoleDto;
use App\Models\Role;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Contracts\Role as SpatieRole;

final class CreateRole
{
    public function handle(RoleDto $roleDto): SpatieRole
    {
        return DB::transaction(function () use ($roleDto): SpatieRole {
            $role = Role::create([
                'name' => $roleDto->name,
            ]);

            if ($roleDto->permissions !== null && $roleDto->permissions !== []) {
                $role->permissions()->sync($roleDto->permissions);
            }

            return $role;
        });
    }
}
