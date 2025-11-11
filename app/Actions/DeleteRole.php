<?php

declare(strict_types=1);

namespace App\Actions;

use App\Models\Role;

final class DeleteRole
{
    public function handle(Role $role): ?bool
    {
        return $role->delete();
    }
}
