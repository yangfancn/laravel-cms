<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\Traits\MetaRequestTrait;
use App\Rules\FileExist;
use Illuminate\Foundation\Http\FormRequest;

class PostRequest extends FormRequest
{
    use MetaRequestTrait;
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
            'title' => 'required|string|min:3|max:255',
            'category_id' => 'required|exists:categories,id',
            'thumb' => ['nullable', new FileExist],
            'summary' => 'nullable|max:255',
            'created_at' => 'nullable|date',
            ...$this->meta()
        ];
    }
}
