<?php

declare(strict_types=1);

namespace App\Actions;

use Spatie\Activitylog\Models\Activity;

final class DeleteActivityLog
{
    public function handle(Activity $activity): ?bool
    {
        return $activity->delete();
    }
}
