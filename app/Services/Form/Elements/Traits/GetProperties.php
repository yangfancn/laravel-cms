<?php

namespace App\Services\Form\Elements\Traits;

use Illuminate\Support\Collection;

/**
 * Trait GetProperties
 * This trait provides functionality to retrieve the properties of a class.
 * It allows accessing public and protected properties of the class.
 *
 * @package App\Services\Form\Elements\Traits
 */
trait GetProperties
{
    /**
     * Get the public or protected properties of the class.
     * @return \Illuminate\Support\Collection 
     */
    public function getProperties(): Collection
    {
        //        $this->modelValue = $this->name;
        $reflect = new \ReflectionClass($this);
        $properties = $reflect->getProperties(\ReflectionProperty::IS_PUBLIC | \ReflectionProperty::IS_PROTECTED);

        return collect($properties)->mapWithKeys(function (\ReflectionProperty $property) {
            $field = $property->getName();

            if (in_array($field, ['field', 'url']) && ! $property->isInitialized($this)) {
                throw new \Exception(get_called_class()."::$field must not be accessed before initialization");
            }

            return $property->isInitialized($this) && $property->getValue($this) !== null
                ? [$field => $property->getValue($this)]
                : [];
        });
    }
}
