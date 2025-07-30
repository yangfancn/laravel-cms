<?php

namespace App\Services\Form\Elements\Traits;

/**
 * Trait UseInput
 * This trait provides a method to enable or disable the use of input in form elements like select colorpicker datapicker.
 *
 * @package App\Services\Form\Elements\Traits
 */
trait UseInput
{
    protected bool $useInput = false;

    /**
     * Set whether to use input in the form element.
     * @param bool $as 
     * @return \App\Services\Form\Elements\Traits\UseInput 
     */
    public function useInput(bool $as = true): self
    {
        $this->useInput = $as;

        return $this;
    }
}
