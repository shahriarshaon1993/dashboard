<?php

declare(strict_types=1);

namespace App\Http\Requests\Settings;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

final class StoreGeneralSettingRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'site_title' => ['required', 'string', 'min:2', 'max:191'],
            'date_format' => ['nullable', 'string', 'min:2', 'max:191'],
            'developed_by' => ['nullable', 'string', 'min:2', 'max:191'],
            'site_logo' => ['nullable', 'image', 'max:2024'],
            'logo_removed' => ['boolean'],
        ];
    }
}
