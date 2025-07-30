<?php

namespace App\Services\Form\Elements;

use App\Services\Form\Elements\Traits\Snap;

/**
 * Slider class represents a slider form element.
 * @package App\Services\Form\Elements
 */
class Slider extends Element
{
    use Snap;

    protected string $field = 'slider';

    protected string $outLabel;

    /**
     * Constructor for the Slider class.
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
}
