<?php

namespace App\Services\Form\Elements\Traits;

/**
 * Trait Clearable
 * This trait provides functionality to make form elements clearable.
 * It allows setting a clear icon and enabling/disabling the clearable feature.
 */
trait Clearable
{
    protected bool $clearable = false;

    protected string $clearIcon = 'close';

    /**
     * Set whether the form element is clearable.
     * If set to true, a clear icon will be displayed, allowing users to clear the input.
     *
     * @return \App\Services\Form\Elements\Traits\Clearable
     */
    public function clearable(bool $able = true): self
    {
        $this->clearable = $able;

        return $this;
    }

    /**
     * Set the icon to be used for clearing the input.
     *
     * @param  string  $icon  example: 'close', 'clear', or any mdi icon name
     * @return \App\Services\Form\Elements\Traits\Clearable
     */
    public function clearIcon(string $icon): self
    {
        $this->clearIcon = $icon;

        return $this;
    }
}
