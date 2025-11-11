<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Models\Module;
use App\Supports\DateFormat;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin Module
 */
final class ModuleResource extends JsonResource
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
            'description' => $this->description,
            'permissions' => PermissionResource::collection(
                $this->whenLoaded('permissions')
            ),
            'created_at' => DateFormat::format($this->created_at),
            'updated_at' => DateFormat::format($this->updated_at),
        ];
    }
}
