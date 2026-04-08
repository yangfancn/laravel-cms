<?php

namespace App\Services\DataTable;

use Illuminate\Support\Collection;

/**
 * Button class for DataTable actions.
 */
class Button
{
    public string $type = 'button';

    public string $color;

    public string $size;

    public string $method;

    public bool $outline;

    public bool $flat;

    public bool $unElevated;

    public bool $rounded;

    public bool $round;

    public bool $square;

    public bool $glossy;

    public bool $withConfirm;

    public ?string $confirmMessage;

    /**
     * Button constructor.
     *
     * @return void
     */
    public function __construct(
        public ?string $label = '',
        public ?string $icon = null,
        public ?string $route = null,
        public ?string $routeParamName = null
    ) {}

    /**
     * Set the button label.
     *
     * @param  string  $size  16px 2rem xs md
     */
    public function size(string $size): self
    {
        $this->size = $size;

        return $this;
    }

    /**
     * Set the HTTP method for the button action.
     */
    public function method(string $method): self
    {
        $this->method = $method;

        return $this;
    }

    /**
     * Set the button outline style.
     */
    public function outline(bool $outline = true): self
    {
        $this->outline = $outline;

        return $this;
    }

    /**
     * Set the button rounded style.
     */
    public function rounded(bool $rounded = true): self
    {
        $this->rounded = $rounded;

        return $this;
    }

    /**
     * Set the button round style.
     */
    public function round(bool $round = true): self
    {
        $this->round = $round;

        return $this;
    }

    /**
     * Set the button flat style.
     */
    public function flat(bool $flat = true): self
    {
        $this->flat = $flat;

        return $this;
    }

    /**
     * Set the button unElevated style.
     * This style is typically used for buttons that do not have a shadow or elevation effect.
     *
     * @param  bool  $unElevated
     */
    public function unElevated(): self
    {
        $this->unElevated = true;

        return $this;
    }

    /**
     * Set the button square style.
     * This style is no border radius
     */
    public function square(bool $square = true): self
    {
        $this->square = $square;

        return $this;
    }

    /**
     * Set the button glossy style.
     */
    public function glossy(bool $glossy = true): self
    {
        $this->glossy = $glossy;

        return $this;
    }

    /**
     * Set the button confirmation.
     * This method allows you to set a confirmation message that will be displayed when the button is
     */
    public function withConfirm(?string $message = null): self
    {
        $this->withConfirm = true;
        $this->confirmMessage = $message;

        return $this;
    }

    /**
     * set the button color.
     *
     * @param  string  $color  'primary''teal''teal-10'
     */
    public function color(string $color): self
    {
        $this->color = $color;

        return $this;
    }

    /**
     * Create a collection of button properties.
     */
    public function make(): Collection
    {
        $reflect = new \ReflectionClass($this);

        return collect($reflect->getProperties(\ReflectionProperty::IS_PUBLIC))
            ->mapWithKeys(
                fn (\ReflectionProperty $property) => $property->isInitialized($this)
                    ? [$property->getName() => $property->getValue($this)]
                    : []
            );
    }
}
