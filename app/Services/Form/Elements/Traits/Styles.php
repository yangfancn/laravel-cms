<?php

namespace App\Services\Form\Elements\Traits;

use App\Services\Form\Enums\Variant;

/**
 * Trait Styles
 * This trait provides styling options for form elements, allowing customization of colors, rounded corners,
 * and various visual styles.
 *
 * @package App\Services\Form\Elements\Traits
 */
trait Styles
{
    /**
     * @var string|null Sets the color of the input when it is not focused.
     */
    protected ?string $labelColor;

    /**
     * @var string|null for example: success or purple or css color (#033 or rgba(255, 0, 0, 0.5))
     */
    protected ?string $bgColor;

    /**
     * @var string|null fontColor
     */
    protected ?string $color;

    /**
     * @var string|int|bool border radius
     */
    protected bool $rounded = false;

    protected ?bool $square = false;

    protected ?bool $centerAffix;

    protected ?bool $standard = null;

    protected ?bool $filled = null;

    protected ?bool $outlined = null;

    protected ?bool $borderless = null;

    protected ?bool $standout = null;

    protected ?bool $dark = null;

    /**
     * Set the label text color of the form element.
     * @param string $color example: 'primay', 'teal' ,'#033', 'rgba(255, 0, 0, 0.5)'
     * @return \App\Services\Form\Elements\Traits\Styles 
     */
    public function labelColor(string $color): self
    {
        $this->labelColor = $color;

        return $this;
    }

    /**
     * Set the background color of the form element.
     * @param string $color example: 'primay', 'teal' ,'#033', 'rgba(255, 0, 0, 0.5)'
     * @return \App\Services\Form\Elements\Traits\Styles 
     */
    public function bgColor(string $color): self
    {
        $this->bgColor = $color;

        return $this;
    }

    /**
     * Set the color of the form element.
     * @param string $color example: 'primay', 'teal' ,'#033', 'rgba(255, 0, 0, 0.5)'
     * @return \App\Services\Form\Elements\Traits\Styles 
     */
    public function color(string $color): self
    {
        $this->color = $color;

        return $this;
    }

    /**
     * Set the rounded corners of the form element.
     * @param bool $round 
     * @return \App\Services\Form\Elements\Traits\Styles 
     */
    public function rounded(bool $round = true): self
    {
        $this->rounded = $round;

        return $this;
    }

    /**
     * Set the square corners of the form element.
     * @param bool $square 
     * @return \App\Services\Form\Elements\Traits\Styles 
     */
    public function square(bool $square = true): self
    {
        $this->square = $square;

        return $this;
    }

    /**
     * Set the center affix alignment of the form element.
     * @param bool $align 
     * @return \App\Services\Form\Elements\Traits\Styles 
     */
    public function centerAffix(bool $align): self
    {
        $this->centerAffix = $align;

        return $this;
    }

    /**
     * Set the standard style of the form element.
     * @param \App\Services\Form\Enums\Variant $variant 
     * @return \App\Services\Form\Elements\Traits\Styles 
     */
    public function variant(Variant $variant): self
    {
        $this->{$variant->value} = true;

        return $this;
    }
}
