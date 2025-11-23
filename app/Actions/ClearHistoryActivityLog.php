<?php

declare(strict_types=1);

namespace App\Actions;

use Illuminate\Validation\ValidationException;
use Spatie\Activitylog\Models\Activity;

final class ClearHistoryActivityLog
{
    public function handle(string $range): mixed
    {
        $time = match ($range) {
            '15m' => now()->subMinutes(15),
            '1h' => now()->subHour(),
            '24h' => now()->subDay(),
            '7d' => now()->subDays(7),
            '4w' => now()->subWeeks(4),
            'all' => null,

            default => throw ValidationException::withMessages([
                'range' => ["Invalid range value: $range"],
            ]),
        };

        $query = Activity::query();

        if ($time !== null) {
            $query->where('created_at', '>=', $time);
        }

        return $query->delete();
    }
}
