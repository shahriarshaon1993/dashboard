<?php

declare(strict_types=1);

use App\Imports\UserImport;
use App\Models\User;

it('can creates user from row data', function (): void {
    $row = [
        'dummy',
        'John Deo',
        'john@gmail.com',
        '01723333333',
        '2025-11-02 19:25:48',
        '2025-11-02 19:25:48',
        'Active',
    ];

    $import = new UserImport();
    $user = $import->model($row);

    expect($user)->toBeInstanceOf(User::class)
        ->name->toBe('John Deo')
        ->email->toBe('john@gmail.com');
});
