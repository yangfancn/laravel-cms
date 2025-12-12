<?php

namespace App\Services\Form\Elements;

use App\Services\Form\Elements\Traits\CanCheckedStyles;
use App\Services\Form\Elements\Traits\CheckedIcon;
use App\Services\Form\Elements\Traits\KeepColor;
use App\Services\Form\Elements\Traits\LeftLabel;

/**
 * Toggle class represents a toggle switch form element.
 */
class Toggle extends Element
{
    use CanCheckedStyles, CheckedIcon, KeepColor, LeftLabel;

    protected string $field = 'toggle';

    public mixed $defaultValue = false;

    protected ?string $icon;

    protected ?string $iconColor;

    /**
     * customer icon
     */
    public function icon(string $icon): self
    {
        $this->icon = $icon;

        return $this;
    }

    /**
     * Set the color of the icon.
     */
    public function iconColor(string $color): self
    {
        $this->iconColor = $color;

        return $this;
    }
}
