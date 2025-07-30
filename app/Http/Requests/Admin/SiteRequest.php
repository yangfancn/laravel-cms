<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\Traits\MetaRequestTrait;
use Illuminate\Foundation\Http\FormRequest;

class SiteRequest extends FormRequest
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
        return array_merge([
            'name' => 'required|string',
        ], $this->meta());
    }
}
