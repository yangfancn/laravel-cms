<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Translation\PotentiallyTranslatedString;

class ExactArrayKeys implements ValidationRule
{
    protected array $expectedKeys;

    public function __construct(array $expectedKeys)
    {
        $this->expectedKeys = $expectedKeys;
    }

    /**
     * Run the validation rule.
     *
     * @param  Closure(string, ?string=): PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (! is_array($value)) {
            $fail(__('validation.array', ['attribute' => $attribute]));

            return;
        }

        $inputKeys = array_keys($value);
        $expectedKeys = $this->expectedKeys;

        sort($inputKeys);
        sort($expectedKeys);

        if ($inputKeys !== $expectedKeys) {
            $fail(
                __('messages.exactlyArrayKeys', [
                    'attribute' => $attribute,
                    'keys' => implode(', ', $this->expectedKeys),
                ])
            );
        }
    }
}
