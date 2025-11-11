<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Http\Resources\UserSharedResource;
use App\Models\GeneralSetting;
use Illuminate\Foundation\Inspiring;
use Illuminate\Http\Request;
use Inertia\Middleware;

final class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that's loaded on the first page visit.
     *
     * @see https://inertiajs.com/server-side-setup#root-template
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determines the current asset version.
     *
     * @see https://inertiajs.com/asset-versioning
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @see https://inertiajs.com/shared-data
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        $quote = is_string(Inspiring::quotes()->random()) ? Inspiring::quotes()->random() : '';
        [$message, $author] = str($quote)->explode('-');

        $settings = GeneralSetting::getSettings();

        return [
            ...parent::share($request),
            'name' => config('app.name'),
            'quote' => [
                'message' => mb_trim($message ?? ''),
                'author' => mb_trim($author ?? ''),
            ],
            'auth.user' => fn (): ?UserSharedResource => $request->user()
                ? new UserSharedResource($request->user())
                : null,
            'flash' => [
                'message' => fn () => $request->session()->get('message'),
                'success' => fn () => $request->session()->get('success'),
                'error' => fn () => $request->session()->get('error'),
            ],
            'settings' => [
                'site_title' => $settings->site_title ?? config('app.name'),
                'timezone' => $settings->timezone ?? config('app.timezone'),
                'date_format' => $settings->date_format ?? config('app.date_format'),
                'site_logo' => $settings->getFirstMediaUrl('site_logo'),
            ],
            'sidebarOpen' => ! $request->hasCookie('sidebar_state') || $request->cookie('sidebar_state') === 'true',
        ];
    }
}
