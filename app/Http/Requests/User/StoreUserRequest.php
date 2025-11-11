<?php

declare(strict_types=1);

namespace App\Http\Requests\User;

use App\Enums\ActiveStatus;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules;

final class StoreUserRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:191', 'unique:users'],
            'phone' => ['nullable', 'min:10', 'max:20', 'unique:users'],
            'is_active' => [Rule::enum(ActiveStatus::class)],

            'password' => ['required', Rules\Password::defaults()],

            'avatar' => ['nullable', 'image', 'max:2056'],
            'avatar_removed' => ['boolean'],

            'roles' => ['nullable', 'array'],
            'permissions' => ['nullable', 'array'],
        ];
    }
}
