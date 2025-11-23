<?php

declare(strict_types=1);

namespace App\Models;

use App\Traits\HasActivityLog;
use Carbon\CarbonInterface;
use Database\Factories\RoleFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Permission\Models\Role as SpiteRole;

/**
 * App\Models\Role
 *
 * @property-read int $id
 * @property-read string $name
 * @property-read string $guard_name
 * @property-read CarbonInterface $created_at
 * @property-read CarbonInterface $updated_at
 */
final class Role extends SpiteRole
{
    /**
     * @use HasFactory<RoleFactory>
     */
    use HasActivityLog, HasFactory, LogsActivity;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'id' => 'integer',
            'name' => 'string',
            'guard_name' => 'string',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }
}
