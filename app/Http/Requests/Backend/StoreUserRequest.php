<?php

namespace App\Http\Requests\Backend;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class StoreUserRequest extends FormRequest
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
            'name' => 'required|regex:/^[\pL\s\-]+$/u|string',
            'email' => 'required|string|email|unique:users',
            'role' => 'required',
            'password' => 'required|confirmed|string',
            'avatar' => 'required|image|mimes:jpeg,png,jpg|max:2048'
        ];
    }
}
