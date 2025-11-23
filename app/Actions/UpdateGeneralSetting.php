<?php

declare(strict_types=1);

namespace App\Actions;

use App\DTOs\GeneralSettingDto;
use App\Models\GeneralSetting;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

final class UpdateGeneralSetting
{
    public function handle(GeneralSettingDto $settingDto): GeneralSetting
    {
        return DB::transaction(function () use ($settingDto): GeneralSetting {
            $setting = GeneralSetting::query()->first();

            $data = [
                'site_title' => $settingDto->site_title,
                'date_format' => $settingDto->date_format,
                'developed_by' => $settingDto->developed_by,
            ];

            if ($setting) {
                $setting->update($data);
            } else {
                $setting = GeneralSetting::create($data);
            }

            if ($settingDto->logo_removed) {
                $setting->clearMedia('site_logo');
            }

            if ($settingDto->site_logo instanceof UploadedFile) {
                $setting->clearMedia('site_logo');

                $setting->addMedia($settingDto->site_logo, 'site_logo');
            }

            Cache::forget('general_settings');
            config([
                'app.timezone' => $setting->timezone,
                'app.site_title' => $setting->site_title,
                'app.date_format' => $setting->date_format,
            ]);

            if ($setting->timezone) {
                date_default_timezone_set($setting->timezone);
            }

            return $setting;
        });
    }
}
