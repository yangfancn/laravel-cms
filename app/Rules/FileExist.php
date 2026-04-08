<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Translation\PotentiallyTranslatedString;

class FileExist implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  Closure(string): PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (
            $value
            && ! (str_starts_with($value, '//') || str_starts_with($value, 'http'))
            && ! file_exists(app()->publicPath().$value)
        ) {
            $fail('file :attribute not exist.');
        }
    }
}
