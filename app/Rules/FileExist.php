<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class FileExist implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (
            $value
            && ! (str_starts_with($value, '//') || str_starts_with($value, 'http'))
            && ! \Storage::disk('public')->exists(ltrim($value, '/storage'))
        ) {
            $fail('file :attribute not exist.');
        }
    }
}
