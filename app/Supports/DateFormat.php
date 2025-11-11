<?php

declare(strict_types=1);

namespace App\Supports;

use Carbon\CarbonInterface;

final class DateFormat
{
    public static function format(CarbonInterface $date, ?string $customFormat = null, ?string $timezone = null): string
    {
        /** @var string $dateFormat */
        $dateFormat = config('app.date_format');

        $date = clone $date;
        if ($timezone !== null && $timezone !== '' && $timezone !== '0') {
            $date->setTimezone($timezone);
        }

        $format = $customFormat ?? $dateFormat;

        return $date->format($format);
    }
}
