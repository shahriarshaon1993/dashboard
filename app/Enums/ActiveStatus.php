<?php

declare(strict_types=1);

namespace App\Enums;

enum ActiveStatus: string
{
    case Active = 'active';
    case Inactive = 'inactive';

    /**
     * @return array<int, array{value: string, label: string}>
     */
    public static function asArray(): array
    {
        return array_map(
            fn (ActiveStatus $case): array => [
                'value' => $case->value,
                'label' => $case->label(),
            ],
            self::cases()
        );
    }

    /**
     * Get the label for the enum value.
     */
    public function label(): string
    {
        return match ($this) {
            self::Active => 'Active',
            self::Inactive => 'Inactive',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::Active => 'default',
            self::Inactive => 'destructive',
        };
    }
}
