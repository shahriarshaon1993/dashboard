<?php

declare(strict_types=1);

use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function (): void {
    Permission::factory()->create(['name' => 'view roles']);
    Permission::factory()->create(['name' => 'create roles']);
    Permission::factory()->create(['name' => 'update roles']);
    Permission::factory()->create(['name' => 'delete roles']);
    Permission::factory()->create(['name' => 'import roles']);
    Permission::factory()->create(['name' => 'export roles']);
});

it('allows user with permission to view roles', function (): void {
    $user = User::factory()->create();
    $role = Role::factory()->create();

    $user->givePermissionTo('view roles');

    expect($user->can('viewAny', $role))->toBeTrue();
});

it('forbids unauthorized user to view roles', function (): void {
    $user = User::factory()->create();
    $role = Role::factory()->create();

    expect($user->can('view', $role))->toBeFalse();
});

it('allows user with permission to create roles', function (): void {
    $user = User::factory()->create();
    $role = Role::factory()->create();

    $user->givePermissionTo('create roles');

    expect($user->can('create', $role))->toBeTrue();
});

it('forbids unauthorized user to create roles', function (): void {
    $user = User::factory()->create();
    $role = Role::factory()->create();

    expect($user->can('view', $role))->toBeFalse();
});

it('allows user with permission to update roles', function (): void {
    $user = User::factory()->create();
    $role = Role::factory()->create();

    $user->givePermissionTo('update roles');

    expect($user->can('update', $role))->toBeTrue();
});

it('forbids unauthorized user to update roles', function (): void {
    $user = User::factory()->create();
    $role = Role::factory()->create();

    expect($user->can('update', $role))->toBeFalse();
});

it('allows user with permission to destroy roles', function (): void {
    $user = User::factory()->create();
    $role = Role::factory()->create();

    $user->givePermissionTo('delete roles');

    expect($user->can('delete', $role))->toBeTrue();
});

it('forbids unauthorized user to destroy roles', function (): void {
    $user = User::factory()->create();
    $role = Role::factory()->create();

    expect($user->can('delete', $role))->toBeFalse();
});

it('allows user with permission to import roles', function (): void {
    $user = User::factory()->create();
    $role = Role::factory()->create();

    $user->givePermissionTo('import roles');

    expect($user->can('import', $role))->toBeTrue();
});

it('forbids unauthorized user to import roles', function (): void {
    $user = User::factory()->create();
    $role = Role::factory()->create();

    expect($user->can('import', $role))->toBeFalse();
});

it('allows user with permission to export roles', function (): void {
    $user = User::factory()->create();
    $role = Role::factory()->create();

    $user->givePermissionTo('export roles');

    expect($user->can('export', $role))->toBeTrue();
});

it('forbids unauthorized user to export roles', function (): void {
    $user = User::factory()->create();
    $role = Role::factory()->create();

    expect($user->can('export', $role))->toBeFalse();
});
