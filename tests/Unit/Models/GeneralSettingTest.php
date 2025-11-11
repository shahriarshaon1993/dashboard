<?php

declare(strict_types=1);

use App\Models\GeneralSetting;

it('to array general settings', function (): void {
    $setting = GeneralSetting::factory()->create()->fresh();

    expect(array_keys($setting->toArray()))
        ->toEqual([
            'id',
            'site_title',
            'date_format',
            'timezone',
            'developed_by',
            'created_at',
            'updated_at',
        ]);
});

it('returns the correct value for an existing key', function (): void {
    GeneralSetting::factory()->create([
        'site_title' => 'My App',
    ]);

    $result = GeneralSetting::getByKey('site_title');

    expect($result)->toBe('My App');
});

it('returns the default value when key does not exist', function (): void {
    GeneralSetting::factory()->create();

    $result = GeneralSetting::getByKey('non_existing_key', 'default_value');

    expect($result)->toBe('default_value');
});

it('returns null when key does not exist and no default is provided', function (): void {
    GeneralSetting::factory()->create();

    $result = GeneralSetting::getByKey('unknown_key');

    expect($result)->toBeNull();
});
