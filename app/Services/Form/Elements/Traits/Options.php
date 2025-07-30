<?php

namespace App\Services\Form\Elements\Traits;

/**
 * Trait Options
 * This trait provides methods to handle options for form elements.
 * 
 * @package App\Services\Form\Elements\Traits
 */
trait Options
{
    protected array $options = [];

    protected bool $column = false;

    /**
     * Set the options for the form element.
     * @param array $options example: ['Option 1', 'Option 2', 'Option 3'] or ['value1' => 'Label 1', 'value2' => 'Label 2']
     * @return \App\Services\Form\Elements\Traits\Options 
     */
    public function options(array $options): self
    {
        $this->options = self::makeOptions($options);

        return $this;
    }

    /**
     * Make options from the provided array.
     * @param array $options 
     * @return array 
     */
    protected static function makeOptions(array $options): array
    {

        return collect($options)->map(fn ($item, $index) => [
            'label' => $item,
            'val' => $index,
        ])->values()->all();
    }

    /**
     * Set whether the options should be displayed in a column layout.
     * @param bool $column 
     * @return \App\Services\Form\Elements\Traits\Options 
     */
    public function column(bool $column = true): self
    {
        $this->column = $column;

        return $this;
    }
}
