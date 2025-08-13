<?php

namespace App\Services\DataTable;

/**
 * ImageColumn class represents a column in a data table that displays images.
 * @package App\Services\DataTable
 */
class ImageColumn extends Column
{
    public string $type = 'image';

    public ?string $width = null;

    public ?string $height = null;

    public ?string $radius = null;

    /**
     * Set the width of the image column.
     * @param int|string $width
     * @return \App\Services\DataTable\ImageColumn
     */
    public function width(int|string $width): self
    {
        $this->width = is_int($width) ? ($width.'px') : $width;
        return $this;
    }

    /**
     * Set the height of the image column.
     * @param int|string $height
     * @return \App\Services\DataTable\ImageColumn
     */
    public function height(int|string $height): self
    {
        $this->height = is_int($height) ? ($height.'px') : $height;

        return $this;
    }

    /**
     * Set the radius of the image column.
     * @param int|string $radius
     * @return \App\Services\DataTable\ImageColumn
     */
    public function radius(int|string $radius): self
    {
        $this->radius = is_int($radius) ? ($radius.'px') : $radius;

        return $this;
    }
}
