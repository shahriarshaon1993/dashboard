<?php

namespace App\Repositories\Backend;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Interface\Backend\ProfileInterface;

class ProfileRepository implements ProfileInterface
{
    public function updateProfileInformation($request)
    {
        // Get login user
        $user = Auth::user();
        // Update user info
        $user->update([
            'name' => $request->name,
            'email' => Str::lower($request->email)
        ]);
        // Image upload
        if ($request->hasFile('avatar')) {
            $fileName = rand() . time() . '.' . $request->file('avatar')->extension();
            $user
                ->addMedia($request->avatar)
                ->usingFileName($fileName)
                ->toMediaCollection('avatar');
        }
    }

    public function updateProdilePassword($request)
    {
        $hashedPassword = Auth::user()->password;

        if(Hash::check($request->current_password, $hashedPassword)) {
            if(!Hash::check($request->password, $hashedPassword)) {
                Auth::user()->update([
                    'password' => Hash::make($request->password)
                ]);
                Auth::logout();
                return redirect()->route('login');
            }else {
                notify()->warning("New password cannot be the same as old password.","warning","topCenter");
            }
        }else {
            notify()->error("Current password not match.","Error","topCenter");
        }
    }
}
