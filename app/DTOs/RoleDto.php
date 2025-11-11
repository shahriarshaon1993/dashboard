<?php

declare(strict_types=1);

namespace App\DTOs;

use Illuminate\Http\Request;

final readonly class RoleDto
{
    /**
     * @param  array<int>|null  $permissions
     */
    public function __construct(
        public string $name,
        public ?array $permissions = null,
    ) {
        //
    }

    public static function from(Request $request): self
    {
        /** @var array<int> $permissions */
        $permissions = $request->array('permissions');

        return new self(
            name: $request->string('name')->value(),
            permissions: $permissions
        );
    }
}
