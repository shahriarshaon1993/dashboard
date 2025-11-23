<?php

declare(strict_types=1);

namespace App\Actions;

use Spatie\Activitylog\Models\Activity;

final class BulkDeleteActivity
{
    /**
     * @param  int[]  $activeIds
     */
    public function handle(array $activeIds): int
    {
        return Activity::destroy($activeIds);
    }
}
