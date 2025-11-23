<?php

declare(strict_types=1);

namespace App\Models;

use Database\Factories\ActivityFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Activitylog\Models\Activity as SpatieActivity;

final class Activity extends SpatieActivity
{
    /**
     * @use HasFactory<ActivityFactory>
     */
    use HasFactory;
}
