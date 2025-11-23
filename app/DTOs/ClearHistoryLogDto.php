<?php

declare(strict_types=1);

namespace App\DTOs;

final readonly class ClearHistoryLogDto
{
    public function __construct(
        public string $range
    ) {}
}
