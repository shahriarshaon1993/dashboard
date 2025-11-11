<?php

declare(strict_types=1);

namespace App\Http\Controllers\Settings;

use App\Http\Requests\Settings\ProfileUpdateRequest;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;

final class ProfileController
{
    /**
     * Show the user's profile settings page.
     */
    public function edit(Request $request): Response
    {
        return Inertia::render('settings/Profile', [
            /** @phpstan-ignore-next-line */
            'mustVerifyEmail' => $request->user() instanceof MustVerifyEmail,
            'status' => $request->session()->get('status'),
        ]);
    }

    /**
     * Update the user's profile information.
     *
     * @codeCoverageIgnore
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        /** @phpstan-ignore-next-line */
        $request->user()->fill(
            Arr::except($request->validated(), ['avatar', 'avatar_removed'])
        );

        /** @phpstan-ignore-next-line */
        if ($request->user()->isDirty('email')) {
            /** @phpstan-ignore-next-line */
            $request->user()->email_verified_at = null;
        }

        if (! empty($request->file('avatar'))) {
            /** @var UploadedFile $avatar */
            $avatar = $request->file('avatar');

            $request->user()?->clearMedia('avatar');
            $request->user()?->addMedia($avatar, 'avatar');
        } elseif (! empty($request->input('avatar_removed'))) {
            $request->user()?->clearMedia('avatar');
        }

        /** @phpstan-ignore-next-line */
        $request->user()->save();

        return to_route('profile.edit')
            ->with('success', 'Profile has been updated successfully.');
    }

    /**
     * Delete the user's profile.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validate([
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        /** @phpstan-ignore-next-line */
        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
