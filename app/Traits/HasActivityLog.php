<?php

declare(strict_types=1);

namespace App\Traits;

use Spatie\Activitylog\LogOptions;

trait HasActivityLog
{
    /**
     * Default activity log options.
     */
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName($this->getLogName())
            ->logAll()->logOnlyDirty()
            ->setDescriptionForEvent(function (string $event): string {
                $userName = auth()->user()->name ?? 'System';

                return "{$this->getLogName()} has been {$event} by {$userName}";
            });
    }

    /**
     * Add IP, user agent, or any custom data.
     */
    public function tapActivity(\Spatie\Activitylog\Models\Activity $activity): void
    {
        $properties = $activity->properties ?? collect();

        $activity->properties = $properties->merge([
            'ip' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
    }

    protected function getLogName(): string
    {
        return class_basename($this);
    }
}
