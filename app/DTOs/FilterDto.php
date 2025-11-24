<?php

declare(strict_types=1);

namespace App\DTOs;

final readonly class FilterDto
{
    public function __construct(
        public ?string $search,
        public string $sort_field,
        public string $sort_order,
        public int $per_page,
    ) {
        //
    }

    /**
     * Create FilterDto from an array.
     *
     * @param  array{search: string|null, sort_field: string, sort_order: string, per_page: int}  $data
     */
    public static function from(array $data): self
    {
        return new self(
            search: $data['search'],
            sort_field: $data['sort_field'],
            sort_order: $data['sort_order'],
            per_page: $data['per_page'],
        );
    }
}
