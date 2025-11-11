<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\User;

final class GeneralSettingPolicy
{
    /**
     * Determine whether the dashboard can view the data.
     */
    public function access(User $user): bool
    {
        return $user->hasPermissionTo('access settings');
    }
}
