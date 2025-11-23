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
        if (! in_array($timezone, [null, '', '0'], true)) {
            $date->setTimezone($timezone);
        }

        $format = $customFormat ?? $dateFormat;

        return $date->format($format);
    }
}
