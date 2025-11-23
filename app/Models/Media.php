<?php

declare(strict_types=1);

namespace App\Models;

use App\Traits\HasActivityLog;
use Carbon\CarbonInterface;
use Database\Factories\MediaFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Spatie\Activitylog\Traits\LogsActivity;

/**
 * App\Models\Media
 *
 * @property-read int $id
 * @property-read string $model_type
 * @property-read int $model_id
 * @property-read string $collection
 * @property-read string $name
 * @property-read string $file_name
 * @property-read string|null $mime_type
 * @property-read int|null $size
 * @property-read array<string, mixed> $custom_properties
 * @property-read CarbonInterface $created_at
 * @property-read CarbonInterface $updated_at
 */
final class Media extends Model
{
    /** @use HasFactory<MediaFactory> */
    use HasActivityLog, HasFactory, LogsActivity;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected $casts = [
        'id' => 'integer',
        'model_type' => 'string',
        'model_id' => 'integer',
        'collection' => 'string',
        'name' => 'string',
        'file_name' => 'string',
        'mime_type' => 'string',
        'size' => 'integer',
        'custom_properties' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * @phpstan-ignore-next-line
     */
    public function model(): MorphTo
    {
        return $this->morphTo();
    }
}
