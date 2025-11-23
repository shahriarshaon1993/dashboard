<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Spatie\Activitylog\Models\Activity;

/**
 * @mixin Activity
 */
final class ActivityLogResource extends JsonResource
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
            'log_name' => $this->log_name,
            'description' => $this->description,
            'causer' => new UserSharedResource($this->whenLoaded('causer')),
            'ip' => $this->properties['ip'] ?? null,
            'user_agent' => $this->properties['user_agent'] ?? null,
            'changes' => $this->properties['attributes'] ?? null,
            'old' => $this->properties['old'] ?? null,
            'created_at' => $this->created_at?->diffForHumans(),
        ];
    }
}
