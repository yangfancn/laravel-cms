<?php

namespace App\Services\Form\Elements\Traits;

/**
 * Trait Counter
 * This trait provides functionality to add a counter to form elements.
 * It allows input elements to display a character count
 */
trait Counter
{
    /**
     * @var bool|int input counter, int is the warning max length
     */
    protected bool $counter = false;

    /**
     * Set whether the form element should display a counter.
     *
     * @return \App\Services\Form\Elements\Traits\Counter
     */
    public function counter(bool $counter = true): self
    {
        $this->counter = $counter;

        return $this;
    }
}
