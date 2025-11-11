<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\User;

final class RolePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('view roles');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user): bool
    {
        return $user->hasPermissionTo('view roles');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasPermissionTo('create roles');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user): bool
    {
        return $user->hasPermissionTo('update roles');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user): bool
    {
        return $user->hasPermissionTo('delete roles');
    }

    /**
     * Determine whether the user can import the model.
     */
    public function import(User $user): bool
    {
        return $user->hasPermissionTo('import roles');
    }

    /**
     * Determine whether the user can export the model.
     */
    public function export(User $user): bool
    {
        return $user->hasPermissionTo('export roles');
    }
}
