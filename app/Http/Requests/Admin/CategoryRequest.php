<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\Traits\MetaRequestTrait;
use Illuminate\Foundation\Http\FormRequest;

class CategoryRequest extends FormRequest
{
    use MetaRequestTrait;
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
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
            'parent_id' => 'nullable|integer|exists:categories,id',
            'directory' => 'required|string',
            'route' => 'nullable|regex:/[a-zA-Z]+(\.[a-zA-Z]+)?/',
            'show' => 'required|boolean',
            'type' => 'required|in:0,1',
            'rank' => 'integer',
            ...$this->meta()
        ];
    }
}
