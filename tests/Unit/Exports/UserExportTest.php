<?php

declare(strict_types=1);

use App\Exports\UserExport;

it('returns correct headings', function (): void {
    $export = new UserExport();

    expect($export->headings())->toEqual([
        'SL',
        'name',
        'email',
        'phone',
        'created_at',
        'updated_at',
        'is_active',
    ]);
});
