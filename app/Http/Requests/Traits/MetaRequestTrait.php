<?php

namespace App\Http\Requests\Traits;

trait MetaRequestTrait
{
    public function meta(): array
    {
        return [
            'meta.title' => 'nullable|string|max:190',
            'meta.keywords' => 'nullable|string|max:255',
            'meta.description' => 'nullable|string|max:255',
            'meta.others' => 'nullable|array',
            'meta.others.*.name' => 'required|string',
            'meta.others.*.value' => 'required|string',
        ];
    }
}
