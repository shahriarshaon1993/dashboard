<?php

declare(strict_types=1);

use App\Models\Permission;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function (): void {
    Permission::factory()->create(['name' => 'view users']);
    Permission::factory()->create(['name' => 'create users']);
    Permission::factory()->create(['name' => 'update users']);
    Permission::factory()->create(['name' => 'delete users']);
    Permission::factory()->create(['name' => 'import users']);
    Permission::factory()->create(['name' => 'export users']);
});

it('allows user with permission to view users', function (): void {
    $user = User::factory()->create();

    $user->givePermissionTo('view users');

    expect($user->can('viewAny', $user))->toBeTrue();
});

it('forbids unauthorized user to view users', function (): void {
    $user = User::factory()->create();

    expect($user->can('view', $user))->toBeFalse();
});

it('allows user with permission to create users', function (): void {
    $user = User::factory()->create();

    $user->givePermissionTo('create users');

    expect($user->can('create', $user))->toBeTrue();
});

it('forbids unauthorized user to create users', function (): void {
    $user = User::factory()->create();

    expect($user->can('view', $user))->toBeFalse();
});

it('allows user with permission to update users', function (): void {
    $user = User::factory()->create();

    $user->givePermissionTo('update users');

    expect($user->can('update', $user))->toBeTrue();
});

it('forbids unauthorized user to update users', function (): void {
    $user = User::factory()->create();

    expect($user->can('update', $user))->toBeFalse();
});

it('allows user with permission to destroy users', function (): void {
    $user = User::factory()->create();

    $user->givePermissionTo('delete users');

    expect($user->can('delete', $user))->toBeTrue();
});

it('forbids unauthorized user to destroy users', function (): void {
    $user = User::factory()->create();

    expect($user->can('delete', $user))->toBeFalse();
});

it('allows user with permission to import users', function (): void {
    $user = User::factory()->create();

    $user->givePermissionTo('import users');

    expect($user->can('import', $user))->toBeTrue();
});

it('forbids unauthorized user to import users', function (): void {
    $user = User::factory()->create();

    expect($user->can('import', $user))->toBeFalse();
});

it('allows user with permission to export users', function (): void {
    $user = User::factory()->create();

    $user->givePermissionTo('export users');

    expect($user->can('export', $user))->toBeTrue();
});

it('forbids unauthorized user to export users', function (): void {
    $user = User::factory()->create();

    expect($user->can('export', $user))->toBeFalse();
});
