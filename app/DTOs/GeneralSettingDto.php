<?php

declare(strict_types=1);

namespace App\DTOs;

use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;

final readonly class GeneralSettingDto
{
    public function __construct(
        public string $site_title,
        public string $date_format = 'd M Y',
        public ?string $developed_by = null,

        public ?UploadedFile $site_logo = null,
        public bool $logo_removed = false,
    ) {
        //
    }

    public static function form(Request $request): self
    {
        return new self(
            site_title: $request->string('site_title')->value(),
            date_format: $request->string('date_format')->value(),
            developed_by: $request->string('developed_by')->value(),
            site_logo: $request->file('site_logo'),
            logo_removed: $request->boolean('logo_removed'),
        );
    }
}
