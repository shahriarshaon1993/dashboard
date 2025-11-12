<?php

declare(strict_types=1);

use App\Models\User;

it('to array user', function (): void {
    $user = User::factory()->create()->fresh();

    expect(array_keys($user->toArray()))
        ->toEqual([
            'id',
            'name',
            'email',
            'phone',
            'email_verified_at',
            'is_active',
            'created_at',
            'updated_at',
            'two_factor_confirmed_at',
        ]);
});
