<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

final class FilterRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'search' => ['nullable', 'string', 'min:1', 'max:255'],
            'sort_field' => ['nullable', 'string', 'min:1', 'max:255'],
            'sort_order' => ['nullable', 'string', 'min:1', 'in:asc,desc'],
            'per_page' => ['nullable', 'integer', 'min:1', 'max:100'],
        ];
    }

    /**
     * Get the validated filters from the request.
     *
     * @return array{search: string|null, sort_field: string, sort_order: string, per_page: int}
     */
    public function validatedFilters(): array
    {
        /** @var array{search: string|null, sort_field: string|null, sort_order: string|null, per_page: int|null} $validated */
        $validated = $this->validated();

        return [
            'search' => $validated['search'] ?? null,
            'sort_field' => $validated['sort_field'] ?? 'id',
            'sort_order' => $validated['sort_order'] ?? 'desc',
            'per_page' => (int) ($validated['per_page'] ?? 15),
        ];
    }
}
