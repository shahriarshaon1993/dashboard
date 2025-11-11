<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Models\GeneralSetting;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin GeneralSetting
 */
final class GeneralSettingResource extends JsonResource
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
            'site_title' => $this->site_title,
            'date_format' => $this->date_format,
            'developed_by' => $this->developed_by,
            'site_logo' => $this->getFirstMediaUrl('site_logo'),
        ];
    }
}
