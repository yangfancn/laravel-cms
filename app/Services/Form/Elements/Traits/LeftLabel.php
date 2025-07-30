<?php

namespace App\Services\Form\Elements\Traits;

/**
 * Trait LeftLabel
 * This trait allows form elements to have their labels positioned on the left side.
 */
trait LeftLabel
{
    protected bool $leftLabel = true;

    /**
     * Set whether the label should be positioned on the left side of the form element.
     * @param bool $leftLabel 
     * @return \App\Services\Form\Elements\Traits\LeftLabel 
     */
    public function leftLabel(bool $leftLabel = true): self
    {
        $this->leftLabel = $leftLabel;

        return $this;
    }
}
