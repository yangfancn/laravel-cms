<?php

namespace App\Services\Form\Elements\Traits;

/**
 * Trait KeepColor
 * This trait allows form elements to maintain their color when checked or unchecked.
 * It can be used in form elements like checkboxes or radio buttons to keep the color styling.
 */
trait KeepColor
{
    protected bool $keepColor = false;

    public function keepColor(bool $keepColor = true): self
    {
        $this->keepColor = $keepColor;

        return $this;
    }
}
