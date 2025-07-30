<?php

namespace App\Http\Requests\Api;

use App\Enums\Votable;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class VoteRequest extends FormRequest
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
            'vote' => 'required|boolean',
            'votable_type' => [
                'required',
                Rule::enum(Votable::class),
            ],
            'votable_id' => 'required|integer',
        ];
    }
}
