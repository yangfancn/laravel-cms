<?php

namespace App\Services\Form\Elements\Traits;

/**
 * Trait UseChips
 * This trait provides a method to enable or disable the use of chips (tags) in form elements.
 *
 * @package App\Services\Form\Elements\Traits
 */
trait UseChips
{
    protected ?bool $useChips = null;

    /**
     * Set whether to use chips (tags) in the form element.
     * @param bool $chips
     * @return \App\Services\Form\Elements\Traits\UseChips
     */
    public function useChips(bool $chips = true): self
    {
        $this->useChips = $chips;

        return $this;
    }
}
