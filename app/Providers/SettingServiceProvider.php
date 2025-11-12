<?php

declare(strict_types=1);

namespace App\Providers;

use App\Models\GeneralSetting;
use Illuminate\Support\ServiceProvider;

final class SettingServiceProvider extends ServiceProvider
{
    /**
     * @codeCoverageIgnoreStart
     */
    public function boot(): void
    {
        if (! app()->environment('testing') && ! app()->runningInConsole()) {
            $settings = GeneralSetting::getSettings();

            if ($settings->timezone) {
                config(['app.timezone' => $settings->timezone]);
                date_default_timezone_set($settings->timezone);
            }

            if ($settings->date_format) {
                config(['app.date_format' => $settings->date_format]);
            }

            config(['app.name' => $settings->site_title ?? config('app.name')]);
        }
    }
}
