<?php

namespace App\Services\DataTable;

/**
 * StatusButton class represents a button in a data states.
 */
class StatusButton extends Button
{
    public string $type = 'status-button';

    public string $positiveColor;

    public string $negativeColor;

    /**
     * Constructor for StatusButton.
     *
     * @return void
     */
    public function __construct(
        public string $positiveLabel,
        public string $positiveIcon,
        public string $negativeLabel,
        public string $negativeIcon,
        public string $statusFiled,
        public ?string $route = null,
        public ?string $routeParamName = null
    ) {}

    /**
     * Set the color for the positive status button.
     */
    public function positiveColor(string $color): self
    {
        $this->positiveColor = $color;

        return $this;
    }

    /**
     * Set the color for the negative status button.
     */
    public function negativeColor(string $color): self
    {
        $this->negativeColor = $color;

        return $this;
    }
}
