<?php

namespace App\Http\Requests\Admin;

use App\Enums\CategoryType;
use App\Http\Requests\Traits\MetaRequestTrait;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

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
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string',
            'parent_id' => 'nullable|integer|exists:categories,id',
            'directory' => 'required|string',
            'show' => 'required|boolean',
            'type' => ['required', new Enum(CategoryType::class)],
            'rank' => 'integer',
            ...$this->meta(),
        ];
    }
}
