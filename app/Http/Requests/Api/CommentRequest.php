<?php

namespace App\Http\Requests\Api;

use App\Enums\Commentable;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CommentRequest extends FormRequest
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
            'content' => [
                'required',
                function ($attribute, $value, $fail) {
                    $textLength = strlen(trim(strip_tags($value)));
                    if ($textLength < 3 || $textLength > 600) {
                        $fail("{$attribute} content length should between 3 and 600 characters.");
                    }
                },
            ],
            'commentable_id' => 'required|integer',
            'commentable_type' => [
                'required',
                Rule::enum(Commentable::class),
            ],
            'comment_id' => 'nullable|exists:comments,id',
        ];
    }
}
