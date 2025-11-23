<?php

declare(strict_types=1);

namespace App\Models;

use App\Traits\HasActivityLog;
use Carbon\CarbonInterface;
use Database\Factories\PermissionFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Permission\Models\Permission as SpatiePermission;

/**
 * App\Models\Permission
 *
 * @property int $id
 * @property string $name
 * @property string $title
 * @property int $module_id
 * @property string $guard_name
 * @property CarbonInterface $created_at
 * @property CarbonInterface $updated_at
 * @property-read Module $module
 */
final class Permission extends SpatiePermission
{
    /** @use HasFactory<PermissionFactory> */
    use HasActivityLog, HasFactory, LogsActivity;

    /**
     * this model belongs to module
     *
     * @return BelongsTo<Module, $this>
     */
    public function module(): BelongsTo
    {
        return $this->belongsTo(Module::class);
    }

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
            'title' => 'string',
            'module_id' => 'integer',
            'guard_name' => 'string',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }
}
