<?php

namespace App\Services\Form\Elements\Traits;

/**
 * Trait CheckedIcon
 * @package App\Services\Form\Elements\Traits
 *
 * This trait provides methods to set checked and unchecked icons for form elements.
 * It can be used in form elements like checkboxes or toggles to customize the icons displayed when checked or unchecked.
 */
trait CheckedIcon
{
    protected ?string $checkedIcon;

    protected ?string $unCheckedIcon;

    /**
     * Set the icon to be displayed when the element is checked.
     * @param string $icon example: 'check-circle', 'check', 'done', or any mdi icon name
     * @return \App\Services\Form\Elements\Traits\CheckedIcon 
     */
    public function checkedIcon(string $icon): self
    {
        $this->checkedIcon = $icon;

        return $this;
    }

    /**
     * Set the icon to be displayed when the element is unchecked.
     * @param string $icon example: 'circle', 'close', 'cancel', or any mdi icon name
     * @return \App\Services\Form\Elements\Traits\CheckedIcon 
     */
    public function unCheckedIcon(string $icon): self
    {
        $this->unCheckedIcon = $icon;

        return $this;
    }
}
