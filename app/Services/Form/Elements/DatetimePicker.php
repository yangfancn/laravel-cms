<?php

namespace App\Services\Form\Elements;

use App\Services\Form\Elements\Traits\HasAffixes;
use App\Services\Form\Elements\Traits\Styles;
use App\Services\Form\Elements\Traits\UseInput;
use Illuminate\Support\Collection;

/**
 * DatetimePicker class represents a datetime picker form element.
 * It allows users to select both date and time, or a range of dates.
 * @package App\Services\Form\Elements
 */
class DatetimePicker extends Element
{
    use HasAffixes, Styles, UseInput;

    protected string $field = 'datetime';

    protected string $mask;

    protected bool $hasDate;

    protected bool $hasTime;

    protected bool $range = false;

    protected function hasDate(): bool
    {
        return true;
    }

    protected function hasTime(): bool
    {
        return true;
    }

    /**
     * its allow to select a range of dates. checked value is an array with keys 'from' and 'to'.
     * @param bool $range 
     * @return \App\Services\Form\Elements\DatetimePicker 
     */
    public function range(bool $range = true): self
    {
        $this->range = $range;
        if ($range) {
            $this->hasTime = false;
            $this->defaultValue = ['from' => null, 'to' => null];
        }

        return $this;
    }

    public function getProperties(): Collection
    {
        $this->hasDate = $this->hasDate();
        $this->hasTime = $this->hasTime();

        if ($this->hasTime && $this->hasDate) {
            $this->mask = 'YYYY-MM-DD HH:mm';
        }

        return parent::getProperties();
    }
}
