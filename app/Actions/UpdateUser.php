<?php

declare(strict_types=1);

namespace App\Actions;

use App\DTOs\UserDto;
use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

final class UpdateUser
{
    /**
     * Handle the action.
     */
    public function handle(UserDto $userDto, User $user): User
    {
        return DB::transaction(function () use ($userDto, $user): User {
            $data = [
                'name' => $userDto->name,
                'email' => $userDto->email,
                'phone' => $userDto->phone,
                'is_active' => $userDto->is_active,
            ];

            if (! in_array($userDto->password, [null, '', '0'], true)) {
                $data['password'] = Hash::make($userDto->password);
            }

            $user->update($data);

            if ($userDto->avatar_removed) {
                $user->clearMedia('avatar');
            }

            if ($userDto->avatar instanceof \Illuminate\Http\UploadedFile) {
                $user->clearMedia('avatar');

                $user->addMedia($userDto->avatar, 'avatar');
            }

            if ($userDto->roles !== null) {
                $roles = Role::find($userDto->roles);

                $user->syncRoles($roles);
            }

            if ($userDto->permissions !== null) {
                $permissions = Permission::find($userDto->permissions);

                $user->syncPermissions($permissions);
            }

            if ($user->is_active->value !== 'active') {
                DB::table('sessions')
                    ->where('user_id', $user->id)
                    ->delete();
            }

            return $user;
        });
    }
}
