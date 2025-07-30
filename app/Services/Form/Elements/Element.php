<?php

namespace App\Services\Form\Elements;

use App\Services\Form\Elements\Traits\GetProperties;

/**
 * Element class represents a base form element with common properties and methods.
 * It can be extended by specific form elements like TextInput, Checkbox, etc.
 * All the element basis on Quasar framework.
 * @package App\Services\Form\Elements
 */
class Element
{
    use GetProperties;

    protected string $field;

    protected ?true $disable = null;

    protected int $cols = 12;

    protected bool $autofocus = false;

    protected mixed $modelValue;

    public mixed $defaultValue = null;

    /**
     * Constructor for the Element class.
     * @param string $name form element name
     * @param string|bool $label form element label, can be a string or boolean (true for default label)
     * @return void 
     */
    public function __construct(public string $name, public string|bool $label) {}

    public static function make(string $name, string|bool $label): static
    {
        return app(static::class, ['name' => $name, 'label' => $label]);
    }

    /**
     * Disable the form element.
     * This method sets the disable property to true, indicating that the element should be disabled.
     * @return \App\Services\Form\Elements\Element 
     */
    public function disable(): self
    {
        $this->disable = true;

        return $this;
    }

    /**
     * Set the number of columns for the form element.
     * This method allows you to specify how many columns the element should span in a flex layout
     * @param int $cols 
     * @return \App\Services\Form\Elements\Element 
     */
    public function cols(int $cols): self
    {
        $this->cols = $cols;

        return $this;
    }

    /**
     * Set whether the form element should have autofocus.
     * This method allows you to specify if the element should automatically receive focus when the page loads, one page can have only one element with autofocus.
     * @param bool $auto 
     * @return \App\Services\Form\Elements\Element 
     */
    public function autofocus(bool $auto = true): self
    {
        $this->autofocus = $auto;

        return $this;
    }

    /**
     * Set the model value for the form element.
     * @param mixed|null $defaultValue 
     * @return \App\Services\Form\Elements\Element 
     */
    public function defaultValue(mixed $defaultValue = null): self
    {
        $this->defaultValue = $defaultValue;

        return $this;
    }
}
