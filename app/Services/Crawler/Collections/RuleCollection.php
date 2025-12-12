<?php

namespace App\Services\Crawler\Collections;

use App\Services\Crawler\Rule;
use IteratorAggregate;
use Traversable;

class RuleCollection implements IteratorAggregate
{
    /**
     * The list of crawled items.
     *
     * @var Rule[]
     */
    private array $list = [];

    public static function make(): self
    {
        return new static;
    }

    /**
     * Add an item to the collection.
     */
    public function add(Rule $item): self
    {
        $this->list[] = $item;

        return $this;
    }

    /**
     * Get all items in the collection.
     *
     * @return Rule[]
     */
    public function all(): array
    {
        return $this->list;
    }

    /**
     * Get an iterator for the collection.
     */
    public function getIterator(): Traversable
    {
        return new \ArrayIterator($this->list);
    }
}
