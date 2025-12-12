<?php

namespace App\Services\Form\Elements\Traits;

/**
 * Trait Snap
 * This trait provides methods to configure a slider with snapping behavior.
 * It allows for customization of the slider's appearance and behavior, such as step size, min/max values, colors, and more.
 */
trait Snap
{
    protected bool $snap = false;

    protected bool $reverse = false;

    protected bool $vertical = false;

    protected bool $labelAlways = false;

    protected int|float $step;

    protected int|float $min;

    protected int|float $max;

    protected int|float $innerMin;

    protected int|float $innerMax;

    protected string $color;

    protected string $trackColor;

    protected string $innerTrackColor;

    protected string $selectionColor;

    protected string $labelColor;

    protected string $labelTextColor;

    protected bool $switchLabelSide;

    protected string $trackSize;

    protected string $thumbSize;

    protected string $thumbColor;

    /**
     * Set whether the slider should snap to the nearest step.
     *
     * @return \App\Services\Form\Elements\Traits\Snap
     */
    public function snap(bool $snap = true): self
    {
        $this->snap = $snap;

        return $this;
    }

    /**
     * Set whether the slider should reverse its direction.
     *
     * @return \App\Services\Form\Elements\Traits\Snap
     */
    public function reverse(bool $reverse = true): self
    {
        $this->reverse = $reverse;

        return $this;
    }

    /**
     * Set whether the slider should be displayed vertically.
     *
     * @return \App\Services\Form\Elements\Traits\Snap
     */
    public function vertical(bool $vertical = true): self
    {
        $this->vertical = $vertical;

        return $this;
    }

    /**
     * Set whether the label should always be visible.
     *
     * @return \App\Services\Form\Elements\Traits\Snap
     */
    public function labelAlways(bool $always = true): self
    {
        $this->labelAlways = $always;

        return $this;
    }

    /**
     * Set the step size for the slider.
     *
     * @return \App\Services\Form\Elements\Traits\Snap
     */
    public function step(float|int $step = 1): self
    {
        $this->step = $step;

        return $this;
    }

    /**
     * Set the minimum value for the slider.
     *
     * @return \App\Services\Form\Elements\Traits\Snap
     */
    public function min(float|int $min = 0): self
    {
        $this->min = $min;

        return $this;
    }

    /**
     * Set the maximum value for the slider.
     *
     * @return \App\Services\Form\Elements\Traits\Snap
     */
    public function max(float|int $max): self
    {
        $this->max = $max;

        return $this;
    }

    /**
     * Set the inner minimum value for the slider.
     *
     * @return \App\Services\Form\Elements\Traits\Snap
     */
    public function innerMin(float|int $innerMin): self
    {
        $this->innerMin = $innerMin;

        return $this;
    }

    /**
     * Set the inner maximum value for the slider.
     *
     * @return \App\Services\Form\Elements\Traits\Snap
     */
    public function innerMax(float|int $innerMax): self
    {
        $this->innerMax = $innerMax;

        return $this;
    }

    /**
     * Set the color of the slider.
     *
     * @return \App\Services\Form\Elements\Traits\Snap
     */
    public function color(string $color): self
    {
        $this->color = $color;

        return $this;
    }

    /**
     * Set the track color of the slider.
     *
     * @return \App\Services\Form\Elements\Traits\Snap
     */
    public function trackColor(string $color): self
    {
        $this->trackColor = $color;

        return $this;
    }

    /**
     * Set the inner track color of the slider.
     *
     * @return \App\Services\Form\Elements\Traits\Snap
     */
    public function innerTrackColor(string $color): self
    {
        $this->innerTrackColor = $color;

        return $this;
    }

    /**
     * Set the selection color of the slider.
     *
     * @return \App\Services\Form\Elements\Traits\Snap
     */
    public function selectionColor(string $color): self
    {
        $this->selectionColor = $color;

        return $this;
    }

    /**
     * Set the label color of the slider.
     *
     * @return \App\Services\Form\Elements\Traits\Snap
     */
    public function labelColor(string $color): self
    {
        $this->labelColor = $color;

        return $this;
    }

    /**
     * Set the text color of the label
     *
     * @return \App\Services\Form\Elements\Traits\Snap
     */
    public function labelTextColor(string $color): self
    {
        $this->labelTextColor = $color;

        return $this;
    }

    /**
     * Set the side of the label.
     *
     * @return \App\Services\Form\Elements\Traits\Snap
     */
    public function switchLabelSide(bool $side = true): self
    {
        $this->switchLabelSide = $side;

        return $this;
    }

    /**
     * Set the size of the thumb.
     *
     * @param  string  $size  example: '12px', '1rem'
     * @return \App\Services\Form\Elements\Traits\Snap
     */
    public function thumbSize(string $size): self
    {
        $this->thumbSize = $size;

        return $this;
    }

    /**
     * Set the color of the thumb.
     *
     * @param  string  $color  example: 'primary', 'teal', or any valid CSS color value
     * @return \App\Services\Form\Elements\Traits\Snap
     */
    public function thumbColor(string $color): self
    {
        $this->thumbColor = $color;

        return $this;
    }

    /**
     * Set the size of the track.
     *
     * @param  string  $size  example: '16px', '2rem'
     * @return \App\Services\Form\Elements\Traits\Snap
     */
    public function trackSize(string $size): self
    {
        $this->trackSize = $size;

        return $this;
    }
}
