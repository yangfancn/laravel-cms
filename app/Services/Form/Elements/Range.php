<?php

namespace App\Services\Form\Elements;

use App\Services\Form\Elements\Traits\Snap;

/**
 * Range class represents a range slider form element.
 * @package App\Services\Form\Elements
 */
class Range extends Element
{
    use Snap;

    protected string $field = 'range';

    protected string $leftLabelColor;

    protected string $leftLabelTextColor;

    protected string $rightLabelColor;

    protected string $rightLabelTextColor;

    protected string $leftThumbColor;

    protected string $rightThumbColor;

    public mixed $defaultValue = ['min' => null, 'max' => null];

    protected string $outLabel;

    /**
     * Constructor for the Range class.
     * @param string $name 
     * @param string $label 
     * @param bool $showLabel 
     * @return void 
     */
    public function __construct(string $name, string $label, bool $showLabel = true)
    {
        parent::__construct($name, $showLabel);
        $this->outLabel = $label;
    }

    /**
     * Set the color for the left label.
     * @param string $color example: 'red', '#ff0000'
     * @return \App\Services\Form\Elements\Range 
     */
    public function leftLabelColor(string $color): self
    {
        $this->leftLabelColor = $color;

        return $this;
    }

    /**
     * Set the text color for the left label.
     * @param string $color example: 'red', '#ff0000'
     * @return \App\Services\Form\Elements\Range 
     */
    public function leftLabelTextColor(string $color): self
    {
        $this->leftLabelTextColor = $color;

        return $this;
    }

    /**
     * Set the color for the right label.
     * @param string $color example: 'red', '#ff0000'
     * @return \App\Services\Form\Elements\Range 
     */
    public function rightLabelColor(string $color): self
    {
        $this->rightLabelColor = $color;

        return $this;
    }

    /**
     * Set the text color for the right label.
     * @param string $color example: 'red', '#ff0000'
     * @return \App\Services\Form\Elements\Range 
     */
    public function rightLabelTextColor(string $color): self
    {
        $this->rightLabelTextColor = $color;

        return $this;
    }

    /**
     * Set the color for the left thumb.
     * @param string $color example: 'red', '#ff0000'
     * @return \App\Services\Form\Elements\Range 
     */
    public function leftThumbColor(string $color): self
    {
        $this->leftThumbColor = $color;

        return $this;
    }

    /**
     * Set the color for the right thumb.
     * @param string $color example: 'red', '#ff0000'
     * @return \App\Services\Form\Elements\Range 
     */
    public function rightThumbColor(string $color): self
    {
        $this->rightThumbColor = $color;

        return $this;
    }
}
