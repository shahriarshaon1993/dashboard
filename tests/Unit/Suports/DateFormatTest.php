<?php

declare(strict_types=1);

use App\Supports\DateFormat;
use Carbon\Carbon;

it('formats date using default app format', function (): void {
    config(['app.date_format' => 'Y-m-d']);

    $date = Carbon::create(2025, 11, 10, 15, 30);
    $formatted = DateFormat::format($date);

    expect($formatted)->toBe('2025-11-10');
});

it('formats date using custom format', function (): void {
    config(['app.date_format' => 'Y-m-d']);

    $date = Carbon::create(2025, 11, 10, 15, 30);
    $formatted = DateFormat::format($date, 'd/m/Y H:i');

    expect($formatted)->toBe('10/11/2025 15:30');
});

it('applies timezone when provided', function (): void {
    config(['app.date_format' => 'Y-m-d H:i']);

    $date = Carbon::create(2025, 11, 10, 15, 30, null, 'UTC');
    $formatted = DateFormat::format($date, null, 'Asia/Dhaka');

    expect($formatted)->toBe(
        $date->copy()->setTimezone('Asia/Dhaka')
            ->format('Y-m-d H:i')
    );
});

it('ignores invalid timezone values', function (): void {
    config(['app.date_format' => 'Y-m-d H:i']);

    $date = Carbon::create(2025, 11, 10, 15, 30, null, 'UTC');
    $formatted = DateFormat::format($date, null, '');

    expect($formatted)->toBe('2025-11-10 15:30');
});
