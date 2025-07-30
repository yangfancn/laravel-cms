<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UserRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => 'required|min:3|max:25',
            'email' => [
                'required',
                'email',
                Rule::unique('users')->ignore($this->route('user')),
            ],
            'password' => [
                $this->route('user') ? 'nullable' : 'required',
                'min:8',
                'max:16',
            ],
            'roles' => 'required|array',
            'roles.*' => 'exists:roles,id',
        ];
    }
}
