<?php

namespace App\Services\Form\Elements;

use App\Services\Form\Elements\Traits\CanCheckedStyles;
use App\Services\Form\Elements\Traits\CheckedIcon;
use App\Services\Form\Elements\Traits\KeepColor;
use App\Services\Form\Elements\Traits\LeftLabel;
use App\Services\Form\Elements\Traits\Options;

/**
 * Radio class represents a radio button form element.
 * @package App\Services\Form\Elements
 */
class Radio extends Element
{
    use CanCheckedStyles, CheckedIcon, KeepColor, LeftLabel, Options;

    protected string $field = 'radio';
}
