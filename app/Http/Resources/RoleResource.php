<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Models\Role;
use App\Supports\DateFormat;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin Role
 */
final class RoleResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'guard_name' => $this->guard_name,
            'permissions' => PermissionResource::collection(
                $this->whenLoaded('permissions')
            ),
            'created_at' => DateFormat::format($this->created_at),
            'updated_at' => DateFormat::format($this->updated_at),
        ];
    }
}
