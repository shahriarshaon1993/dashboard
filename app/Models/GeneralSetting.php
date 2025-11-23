<?php

declare(strict_types=1);

namespace App\Models;

use App\Traits\HasActivityLog;
use App\Traits\HasMedia;
use Carbon\CarbonInterface;
use Database\Factories\GeneralSettingFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Spatie\Activitylog\Traits\LogsActivity;

/**
 * App\Models\GeneralSetting
 *
 * @property-read int $id
 * @property-read string $site_title
 * @property-read string $date_format
 * @property-read string $timezone
 * @property-read string $developed_by
 * @property-read CarbonInterface $created_at
 * @property-read CarbonInterface $updated_at
 */
final class GeneralSetting extends Model
{
    /**
     * @use HasMedia<GeneralSetting>
     * @use HasFactory<GeneralSettingFactory>
     */
    use HasActivityLog, HasFactory, HasMedia, LogsActivity;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected $casts = [
        'id' => 'integer',
        'site_title' => 'string',
        'date_format' => 'string',
        'timezone' => 'string',
        'developed_by' => 'string',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public static function getSettings(): self
    {
        /** @var static $settings */
        $settings = Cache::rememberForever(
            'general_settings', fn (): self => self::with('media')->first() ?? new self()
        );

        return $settings;
    }

    public static function getByKey(string $key, ?string $default = null): mixed
    {
        $settings = self::getSettings();

        return $settings->$key ?? $default;
    }
}
