<?php

declare(strict_types=1);

namespace App\DTOs;

use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;

final readonly class UserDto
{
    /**
     * @param  array<int>  $roles
     * @param  array<int>  $permissions
     */
    public function __construct(
        public string $name,
        public string $email,
        public string $phone,
        public string $is_active,

        public ?string $password = null,

        public ?UploadedFile $avatar = null,
        public bool $avatar_removed = false,

        public ?array $roles = null,
        public ?array $permissions = null,
    ) {
        //
    }

    public static function from(Request $request): self
    {
        /** @var array<int> $roles */
        $roles = $request->array('roles');

        /** @var array<int> $permissions */
        $permissions = $request->array('permissions');

        return new self(
            name: $request->string('name')->value(),
            email: $request->string('email')->value(),
            phone: $request->string('phone')->value(),
            is_active: $request->string('is_active')->value(),
            password: $request->string('password')->value(),
            avatar: $request->file('avatar'),
            avatar_removed: $request->boolean('avatar_removed'),
            roles: $roles,
            permissions: $permissions,
        );
    }
}
