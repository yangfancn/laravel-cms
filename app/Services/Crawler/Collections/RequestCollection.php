<?php

namespace App\Services\Crawler\Collections;

use GuzzleHttp\Psr7\Request;

class RequestCollection
{
    /**
     * The list of crawled items.
     *
     * @var array
     */
    private array $list = [];

    /**
     * Add an item to the collection.
     *
     * @param Request $item
     */
    public function add(Request $item): void
    {
        $this->list[] = $item;
    }

    /**
     * Get all items in the collection.
     *
     * @return array
     */
    public function all(): array
    {
        return $this->list;
    }
}
