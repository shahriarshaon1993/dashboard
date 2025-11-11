<?php

declare(strict_types=1);

use App\Models\Permission;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function (): void {
    Permission::factory()->create([
        'name' => 'access dashboard',
    ]);
});

it('allows user with permission to access dashboard', function (): void {
    $user = User::factory()->create();

    $user->givePermissionTo('access dashboard');

    $response = $this->actingAs($user)
        ->get(route('dashboard'));

    $response->assertOk();
});

it('forbids unauthorized user to access dashboard', function (): void {
    $user = User::factory()->create();

    $response = $this->actingAs($user)
        ->get(route('dashboard'));

    $response->assertForbidden();
});
