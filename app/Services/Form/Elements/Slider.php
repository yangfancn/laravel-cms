<?php

namespace App\Services\Form\Elements;

use App\Services\Form\Elements\Traits\Snap;

/**
 * Slider class represents a slider form element.
 */
class Slider extends Element
{
    use Snap;

    protected string $field = 'slider';

    protected string $outLabel;

    /**
     * Constructor for the Slider class.
     *
     * @return void
     */
    public function __construct(string $name, string $label, bool $showLabel = true)
    {
        parent::__construct($name, $showLabel);
        $this->outLabel = $label;
    }
}
