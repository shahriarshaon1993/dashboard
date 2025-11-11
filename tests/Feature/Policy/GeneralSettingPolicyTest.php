<?php

declare(strict_types=1);

use App\Models\GeneralSetting;
use App\Models\Permission;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function (): void {
    Permission::factory()->create(['name' => 'access settings']);
});

it('allows user with permission to access settings', function (): void {
    $user = User::factory()->create();
    $setting = GeneralSetting::factory()->create();

    $user->givePermissionTo('access settings');

    expect($user->can('access', $setting))->toBeTrue();
});

it('forbids unauthorized user to access settings', function (): void {
    $user = User::factory()->create();
    $setting = GeneralSetting::factory()->create();

    expect($user->can('access', $setting))->toBeFalse();
});
