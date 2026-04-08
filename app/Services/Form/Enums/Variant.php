<?php

namespace App\Services\Form\Enums;

enum Variant: string
{
    case STANDARD = 'standard';
    case OUTLINED = 'outlined';
    case FILLED = 'filled';
    case BORDERLESS = 'borderless';
    case STANDOUT = 'standout';
}
