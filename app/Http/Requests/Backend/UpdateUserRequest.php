<?php

namespace App\Http\Requests\Backend;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|regex:/^[\pL\s\-]+$/u|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,'. $this->user->id,
            'role' => 'required',
            'password' => 'nullable|confirmed|string|min:8',
            'avatar' => 'nullable|image'
        ];
    }
}
