<?php

namespace App\Services\Crawler\Collections;

use GuzzleHttp\Psr7\Request;

class RequestCollection
{
    /**
     * The list of crawled items.
     */
    private array $list = [];

    /**
     * Add an item to the collection.
     */
    public function add(Request $item): void
    {
        $this->list[] = $item;
    }

    /**
     * Get all items in the collection.
     */
    public function all(): array
    {
        return $this->list;
    }
}
