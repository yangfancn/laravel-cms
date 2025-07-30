<?php

namespace App\Services\Form\Elements\Traits;

/**
 * Trait HasAffixes
 * This trait provides methods to add affixes (prefix, suffix, prepend icon, append icon) to form elements.
 * It can be used in form elements like text inputs or selects to customize the appearance with icons or text.
 *
 * @package App\Services\Form\Elements\Traits
 */
trait HasAffixes
{
    protected ?string $appendIcon = null;

    protected ?string $prefix = null;

    protected ?string $suffix = null;

    protected ?string $prependIcon = null;

    /**
     * Set the prefix for the form element.
     * @param string $icon example: 'mdi-account' 'mdi-lock' or any mdi icon name
     * @return \App\Services\Form\Elements\Traits\HasAffixes 
     */
    public function appendIcon(string $icon): self
    {
        $this->appendIcon = $icon;

        return $this;
    }

    /**
     * Set the prefix for the form element.
     * @param string $str example: 'USD', '€', or any text to be displayed before the input
     * @return \App\Services\Form\Elements\Traits\HasAffixes 
     */
    public function prefix(string $str): self
    {
        $this->prefix = $str;

        return $this;
    }

    /**
     * Set the suffix for the form element.
     * @param string $str example: 'USD', '€', or any text to be displayed after the input
     * @return \App\Services\Form\Elements\Traits\HasAffixes 
     */
    public function suffix(string $str): self
    {
        $this->suffix = $str;

        return $this;
    }

    /**
     * Set the prepend icon for the form element.
     * @param string $icon example: 'mdi-account', 'mdi-lock', or any mdi icon name
     * @return \App\Services\Form\Elements\Traits\HasAffixes 
     */    
    public function prependIcon(string $icon): self
    {
        $this->prependIcon = $icon;

        return $this;
    }
}
