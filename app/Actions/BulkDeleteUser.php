<?php

declare(strict_types=1);

namespace App\Actions;

use App\Models\User;

final class BulkDeleteUser
{
    /**
     * @param  int[]  $userIds
     */
    public function handle(array $userIds): int
    {
        return User::destroy($userIds);
    }
}
