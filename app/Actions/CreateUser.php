<?php

declare(strict_types=1);

namespace App\Actions;

use App\DTOs\UserDto;
use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

final class CreateUser
{
    /**
     * Handle the action.
     */
    public function handle(UserDto $userDto): User
    {
        return DB::transaction(function () use ($userDto): User {
            /** @var string $password */
            $password = $userDto->password;

            $user = User::create([
                'name' => $userDto->name,
                'email' => $userDto->email,
                'phone' => $userDto->phone,
                'is_active' => $userDto->is_active,
                'password' => Hash::make($password),
            ]);

            if ($userDto->avatar instanceof UploadedFile) {
                $user->addMedia($userDto->avatar, 'avatar');
            }

            if ($userDto->roles !== null && $userDto->roles !== []) {
                $roles = Role::find($userDto->roles);

                $user->syncRoles($roles);
            }

            if ($userDto->permissions !== null && $userDto->permissions !== []) {
                $permissions = Permission::find($userDto->permissions);

                $user->syncPermissions($permissions);
            }

            return $user;
        });
    }
}
