<?php

declare(strict_types=1);

namespace App\Actions;

use App\DTOs\RoleDto;
use App\Models\Role;
use Illuminate\Support\Facades\DB;

final class UpdateRole
{
    /**
     * Handle the action.
     */
    public function handle(RoleDto $roleDto, Role $role): Role
    {
        return DB::transaction(function () use ($roleDto, $role): Role {
            $role->update([
                'name' => $roleDto->name,
            ]);

            if ($roleDto->permissions !== null && $roleDto->permissions !== []) {
                $role->permissions()->sync($roleDto->permissions);
            }

            return $role;
        });
    }
}
