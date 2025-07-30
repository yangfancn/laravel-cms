<?php

namespace App\Services\Form\Elements\Traits;

/**
 * Trait CanCheckedStyles
 * @package App\Services\Form\Elements\Traits
 */
trait CanCheckedStyles
{
    protected ?string $size;

    protected ?string $color;

    /**
     * Set the size of the icon.
     * @param  string  $iconSize  example: 12px or 1rem or xs/md/lg
     * @return \App\Services\Form\Elements\Traits\CanCheckedStyles
     * @example
     * ```php
     * $checkbox->size('12px'); // Set icon size to 12 pixels
     * $checkbox->size('1rem'); // Set icon size to 1 rem
     * $checkbox->size('xs');   // Set icon size to extra small
     * $checkbox->size('md');   // Set icon size to medium
     * $checkbox->size('lg');   // Set icon size to large
     * ```
     */
    public function size(string $iconSize): self
    {
        $this->size = $iconSize;

        return $this;
    }

    /**
     * Set the color of the icon.
     * @param string $color example: 'red', 'blue', 'green', or any valid CSS color value
     * @return \App\Services\Form\Elements\Traits\CanCheckedStyles 
     */
    public function color(string $color): self
    {
        $this->color = $color;

        return $this;
    }
}
