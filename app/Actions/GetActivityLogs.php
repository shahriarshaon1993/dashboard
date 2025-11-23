<?php

declare(strict_types=1);

namespace App\Actions;

use App\DTOs\FilterDto;
use App\Http\Resources\ActivityLogResource;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Spatie\Activitylog\Models\Activity;

final class GetActivityLogs
{
    /**
     * handle getting logs with filters.
     *
     * @return array{filters: FilterDto, logs: AnonymousResourceCollection}
     */
    public function handle(FilterDto $filters): array
    {
        $logs = Activity::query()->with('causer')
            ->when(isset($filters->search), function (Builder $q) use ($filters): void {
                $q->where(function (Builder $query) use ($filters): void {
                    $query->where('log_name', 'LIKE', "%{$filters->search}%")
                        ->orWhere('description', 'LIKE', "%{$filters->search}%")
                        ->orWhere('subject_type', 'LIKE', "%{$filters->search}%")
                        ->orWhere('event', 'LIKE', "%{$filters->search}%")
                        ->orWhereDate('created_at', 'LIKE', "%{$filters->search}%")
                        ->orWhereHas('causer', function (Builder $q) use ($filters): void {
                            $q->where('name', 'LIKE', "%{$filters->search}%");
                        });
                });
            })
            ->orderBy($filters->sort_field ?? 'id', $filters->sort_order ?? 'asc')
            ->paginate($filters->per_page)
            ->withQueryString();

        return [
            'filters' => $filters,
            'logs' => ActivityLogResource::collection($logs),
        ];
    }
}
