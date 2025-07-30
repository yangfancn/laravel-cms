<?php

namespace App\Services\Form\Elements;

/**
 * DatePicker class represents a date picker form element.
 * @package App\Services\Form\Elements
 */
class DatePicker extends DatetimePicker
{
    protected function hasTime(): bool
    {
        return false;
    }
}
