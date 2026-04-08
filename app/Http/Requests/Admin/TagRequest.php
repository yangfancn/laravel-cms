<?php

namespace App\Http\Requests\Admin;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class TagRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => [
                'required',
                'string',
                'between:2,40',
                Rule::unique('tags')->ignore($this->route('tag')),
            ],
            'slug' => [
                'nullable',
                Rule::unique('slugs', 'name')
                    ->ignore($this->route('tag')?->slug?->id),
            ],
        ];
    }
}
