<?php

namespace App\Services\Form\Elements;

use App\Services\Form\Elements\Traits\HasAffixes;
use App\Services\Form\Elements\Traits\Styles;
use App\Services\Form\Elements\Traits\UseInput;
use App\Services\Form\Enums\ColorView;

/**
 * ColorPicker class represents a color picker form element.
 * @package App\Services\Form\Elements
 */
class ColorPicker extends Element
{
    use HasAffixes, Styles, UseInput;

    protected string $field = 'color';

    protected array $palette = [];

    protected bool $noHeader = false;

    protected bool $noHeaderTabs = false;

    protected bool $noFooter = false;

    protected ColorView $defaultView = ColorView::SPECTRUM;

    /**
     * Set the default view for the color picker.
     * @param \App\Services\Form\Enums\ColorView $colorView 
     * @return \App\Services\Form\Elements\ColorPicker 
     */
    public function defaultView(ColorView $colorView): self
    {
        $this->defaultView = $colorView;

        return $this;
    }

    /**
     * Set the color palette for the color picker.
     * @param array $arr example: ['#ff0000', '#00ff00', '#0000ff']
     * @return \App\Services\Form\Elements\ColorPicker 
     */
    public function palette(array $arr): self
    {
        $this->palette = $arr;

        return $this;
    }

    /**
     * Set whether to hide the header of the color picker.
     * @param bool $noHeader 
     * @return \App\Services\Form\Elements\ColorPicker 
     */
    public function noHeader(bool $noHeader = true): self
    {
        $this->noHeader = $noHeader;

        return $this;
    }

    /**
     * Set whether to hide the header tabs of the color picker.
     * @param bool $noHeaderTabs 
     * @return \App\Services\Form\Elements\ColorPicker 
     */
    public function noHeaderTabs(bool $noHeaderTabs = true): self
    {
        $this->noHeaderTabs = $noHeaderTabs;

        return $this;
    }

    /**
     * Set whether to hide the footer of the color picker.
     * @param bool $noFooter 
     * @return \App\Services\Form\Elements\ColorPicker 
     */
    public function noFooter(bool $noFooter = true): self
    {
        $this->noFooter = $noFooter;

        return $this;
    }
}
