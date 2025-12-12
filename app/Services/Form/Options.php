<?php

namespace App\Services\Form;

use App\Services\Form\Elements\Element;
use Illuminate\Support\Collection;

/**
 * form options trait
 */
trait Options
{
    /**
     * @var Collection<Element> | null
     */
    protected ?Collection $options = null;

    /**
     * Add form element
     *
     * @return \App\Services\Form\Options
     */
    public function add(Element|Block $element): self
    {
        if (! $this->options) {
            $this->options = collect();
        }

        $this->options[] = $element;

        if (! property_exists($this, 'data')
            || ! $this->data
            || ! (is_array($this->data) ? array_key_exists($element->name, $this->data) : property_exists($this->data, $element->name))
            || $this->data[$element->name] === null
        ) {
            $this->data[$element->name] = $element->defaultValue;
        }

        return $this;
    }
}
