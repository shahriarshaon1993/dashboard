<?php

declare(strict_types=1);

namespace App\DTOs;

final readonly class BulkDestroyDto
{
    /**
     * @param  int[]  $ids
     */
    public function __construct(
        public array $ids,
    ) {
        //
    }

    /**
     * @param  array{ids: int[]}  $data
     */
    public static function from(array $data): self
    {
        return new self(
            ids: $data['ids'],
        );
    }
}
