<?php

namespace App\Repositories\Backend;

use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Interface\Backend\UserInterface;

class UserRepository implements UserInterface
{
    /**
     * get all user who are delete or soft delete
     *
     * @param  mixed $request
     * @return void
     */
    public function getAllUsers($request)
    {
        return User::with('role')
                ->when($request->has('archive'), function($query) {
                    $query->onlyTrashed();
                })->get();
    }

    /**
     * Get all roles
     *
     * @return void
     */
    public function getAllRoles()
    {
        return Role::select('id', 'name')->get();
    }

    public function storeUpdateUser($request, $user = null)
    {
        if($user)
        {
            $user->update([
                'role_id' => $request->role,
                'name' => $request->name,
                'email' => $request->email,
                'password' => isset($request->password) ? Hash::make($request->password) : $user->password,
                'status' => $request->filled('status'),
            ]);
            if ($request->hasFile('avatar')) {
                $fileName = rand() . time() . '.' . $request->file('avatar')->extension();
                $user->addMedia($request->avatar)
                    ->usingFileName($fileName)
                    ->toMediaCollection('avatar');
            }

        }else {
            $user = User::create([
                'role_id' => $request->role,
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'status' => $request->filled('status'),
            ]);
            // upload images
            if ($request->hasFile('avatar')) {
                $fileName = rand() . time() . '.' . $request->file('avatar')->extension();
                $user->addMedia($request->avatar)
                    ->usingFileName($fileName)
                    ->toMediaCollection('avatar');
            }
        }
    }
}