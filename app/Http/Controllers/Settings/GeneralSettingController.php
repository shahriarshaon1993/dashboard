<?php

declare(strict_types=1);

namespace App\Http\Controllers\Settings;

use App\Actions\CreateGeneralSetting;
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
    public function create(): Response
    {
        Gate::authorize('access', User::class);

        $setting = GeneralSetting::query()
            ->with('media')
            ->first();

        return Inertia::render('settings/GeneralSetting', [
            'setting' => new GeneralSettingResource($setting),
        ]);
    }

    public function update(StoreGeneralSettingRequest $request, CreateGeneralSetting $action): RedirectResponse
    {
        Gate::authorize('access', User::class);

        $settingDto = GeneralSettingDto::form($request);

        $action->handle($settingDto);

        return to_route('general-settings.create')
            ->with('success', 'General setting update successfully!');
    }
}
