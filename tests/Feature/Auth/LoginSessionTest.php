<?php

declare(strict_types=1);

use App\Enums\ActiveStatus;
use App\Models\User;

test('users can logged in using the login screen', function (): void {
    $user = User::factory()->withoutTwoFactor()->create();

    $response = $this->post(route('auth.login'), [
        'email' => $user->email,
        'password' => 'password',
    ]);

    $this->assertAuthenticated();
    $response->assertRedirect(route('dashboard', absolute: false));
});

it('throws validation exception if user is inactive', function (): void {
    $user = User::factory()->withoutTwoFactor()->create([
        'is_active' => ActiveStatus::Inactive->value,
    ]);

    $response = $this->post(route('auth.login'), [
        'email' => $user->email,
        'password' => 'password',
    ]);

    $response->assertSessionHasErrors(['email']);
});
