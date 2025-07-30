<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class PermissionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return \Auth::check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string',
            'admin_menu' => 'nullable|array',
            'admin_menu.label' => 'requiredWith:admin_menu.route,admin_menu.icon,admin_menu.parent_id',
            'admin_menu.route' => 'nullable|regex:/[a-zA-Z]+(\.[a-zA-Z]+)?/',
            'admin_menu.icon' => 'requiredWith:admin_menu.label',
            'admin_menu.parent_id' => 'nullable|integer',
        ];
    }
}
