<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Models\User;
use App\Supports\DateFormat;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin User
 */
final class UserResource extends JsonResource
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
            'email' => $this->email,
            'phone' => $this->phone,
            'is_active' => $this->is_active,
            'status' => [
                'value' => $this->is_active->value,
                'label' => $this->is_active->label(),
                'color' => $this->is_active->color(),
            ],
            'avatar' => $this->whenLoaded('media', fn (): ?string => $this->getFirstMediaUrl('avatar')),
            'created_at' => DateFormat::format($this->created_at),
            'updated_at' => DateFormat::format($this->updated_at),
            'roles' => RoleResource::collection($this->whenLoaded('roles')),
            'permissions' => PermissionResource::collection($this->whenLoaded('permissions')),
        ];
    }
}
