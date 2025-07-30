<?php

namespace App\Services\DataTable;

use App\Services\DataTable\enums\Align;
use Illuminate\Support\Collection;

/**
 * Column class represents a column in a data table.
 * It defines properties such as name, label, alignment, and whether the column is sortable.
 * The class provides a method to create a collection of column properties.
 * Subclasses should define the $type property to specify the type of column (e.g., 'text', 'image', 'link').
 * @package App\Services\DataTable
 */
abstract class Column
{
    public string $field;

    public string $type;

    public function __construct(
        public string $name,
        public string $label,
        public Align $align = Align::LEFT,
        public bool $sortable = false,
    ) {
        $this->field = $this->name;
    }

    public function make(): Collection
    {
        if (empty($this->type)) {
            throw new \InvalidArgumentException('The $type property must be defined in the subclass.');
        }

        $reflect = new \ReflectionClass($this);

        return collect($reflect->getProperties(\ReflectionProperty::IS_PUBLIC))
            ->mapWithKeys(
                fn (\ReflectionProperty $property) => [$property->getName() => $property->getValue($this)]
            );
    }
}
