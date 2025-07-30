<?php

namespace App\Services\Form\Elements\Traits;

/**
 * Trait Multiple
 * This trait allows form elements to support multiple selections.
 * It can be used in form elements like selects or checkboxes to enable multiple selection functionality.
 *
 * @package App\Services\Form\Elements\Traits
 */
trait Multiple
{
    protected bool $multiple = false;

    /**
     * Set whether the form element supports multiple selections.
     * @param bool $multiple
     * @return \App\Services\Form\Elements\Traits\Multiple
     */
    public function multiple(bool $multiple = true): self
    {
        $this->multiple = $multiple;

        return $this;
    }
}
