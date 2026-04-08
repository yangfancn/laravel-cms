<?php

namespace App\Enums\Traits;

trait EnumOptions
{
    public static function options(): array
    {
        return collect(self::cases())
            ->mapWithKeys(fn ($case) => [
                $case->name => $case->value,
            ])
            ->all();
    }
}
