<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;

class UploadRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Route is already behind admin auth; allow here.
        return true;
    }

    public function rules(): array
    {
        $maxKb = (int) config('upload.max_kb', 2048);
        $mimes = (string) config('upload.mimes', 'jpg,jpeg,png,webp,gif,pdf');

        return [
            'file' => [
                'required',
                'file',
                "mimes:$mimes",
                "max:$maxKb", // kilobytes
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'file.mimes' => __('messages.disallowFileMimes', [
                'mimes' => config('upload.mimes', 'jpg,jpeg,png,webp,gif,pdf')
            ]),
            'file.max' => __('messages.exceedsAllowSize', [
                'size' => config('upload.max_kb', 2048) . 'kb'
            ]),
        ];
    }

    public function prepareForValidation(): void
    {
        $file = $this->file('file');

        if ($file && $file->getError() === UPLOAD_ERR_INI_SIZE) {
            throw ValidationException::withMessages([
                'file' => __('messages.exceedsAllowSize', [
                    'size' => ini_get('upload_max_filesize')
                ]),
            ]);
        }
    }
}

