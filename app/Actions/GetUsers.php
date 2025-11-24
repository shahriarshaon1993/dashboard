<?php

declare(strict_types=1);

namespace App\Actions;

use App\DTOs\FilterDto;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

final class GetUsers
{
    /**
     * handle getting users with filters.
     *
     * @return array{filters: FilterDto, users: AnonymousResourceCollection}
     */
    public function handle(FilterDto $filters): array
    {
        $users = User::query()
            ->with(['media'])
            ->when(isset($filters->search), function (Builder $q) use ($filters): void {
                $q->where('name', 'LIKE', '%'.$filters->search.'%')
                    ->orWhere('email', 'LIKE', '%'.$filters->search.'%')
                    ->orWhere('phone', 'LIKE', '%'.$filters->search.'%');
            })
            ->orderBy($filters->sort_field ?? 'id', $filters->sort_order ?? 'asc')
            ->paginate($filters->per_page)
            ->withQueryString();

        return [
            'filters' => $filters,
            'users' => UserResource::collection($users),
        ];
    }
}
