<?php

namespace App\Services\Form\Elements;

/**
 * TimePicker class represents a time picker form element.
 * @package App\Services\Form\Elements
 */
class TimePicker extends DatetimePicker
{
    protected function hasDate(): bool
    {
        return false;
    }
}
