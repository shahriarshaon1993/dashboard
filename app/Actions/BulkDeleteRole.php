<?php

declare(strict_types=1);

namespace App\Actions;

use App\Models\Role;

final class BulkDeleteRole
{
    /**
     * @param  int[]  $roleIds
     */
    public function handle(array $roleIds): int
    {
        return Role::destroy($roleIds);
    }
}
