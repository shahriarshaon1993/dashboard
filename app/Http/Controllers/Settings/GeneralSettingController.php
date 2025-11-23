<?php

declare(strict_types=1);

namespace App\Http\Controllers\Settings;

use App\Actions\UpdateGeneralSetting;
use App\DTOs\GeneralSettingDto;
use App\Http\Requests\Settings\StoreGeneralSettingRequest;
use App\Http\Resources\GeneralSettingResource;
use App\Models\GeneralSetting;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;
use Inertia\Response;

final class GeneralSettingController
{
    public function edit(): Response
    {
        Gate::authorize('access', User::class);

        return Inertia::render('settings/GeneralSetting', [
            'setting' => GeneralSettingResource::make(
                GeneralSetting::query()->with('media')->first(),
            ),
        ]);
    }

    public function update(StoreGeneralSettingRequest $request, UpdateGeneralSetting $action): RedirectResponse
    {
        Gate::authorize('access', User::class);

        $action->handle(GeneralSettingDto::form($request));

        return to_route('general-settings.edit')
            ->with('success', 'General setting update successfully!');
    }
}
