<?php

namespace App\Services\DataTable;

use App\Services\DataTable\enums\Align;

/**
 * LinkColumn class represents a column in a data table that displays links.
 * @package App\Services\DataTable
 */
class LinkColumn extends Column
{
    public string $target = 'self';

    /**
     * Constructor for LinkColumn.
     * @param string $name 
     * @param string $urlField 
     * @param string $label 
     * @param \App\Services\DataTable\enums\Align $align 
     * @param bool $sortable 
     * @return void 
     */
    public function __construct(string $name, public string $urlField, string $label, Align $align = Align::LEFT, bool $sortable = false)
    {
        parent::__construct($name, $label, $align, $sortable);
        $this->type = 'link';
    }

    /**
     * Set the target attribute for the link.
     * @param string $target 
     * @return \App\Services\DataTable\LinkColumn 
     */
    public function target(string $target): self
    {
        $this->target = $target;

        return $this;
    }
}
