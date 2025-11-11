<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\User;

final class DashboardPolicy
{
    /**
     * Determine whether the dashboard can view the data.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('access dashboard');
    }
}
