<?php

namespace App\Enums;

use App\Enums\Traits\EnumOptions;

enum CategoryType: string
{
    use EnumOptions;
    case View = 'view';
    case Posts = 'posts';
}
