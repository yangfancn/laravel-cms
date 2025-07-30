<?php

namespace App\Services\Crawler;

use Closure;

class Rule
{
    public ?Closure $callback = null;

    public function __construct(
        public string $name,
        public string $selector,
        public string $attribute,
        public array $filterSelector,
    ) {}

    /**
     * Create a new Rule instance.
     *
     * @param string $name
     * @param string $selector css selector
     * @param string $attribute which attribute value to use
     * @param string[] $filterSelector css selectors to filter the elements
     * @return static
     */
    public static function make(
        string $name,
        string $selector,
        string $attribute = 'text',
        array $filterSelector = []
    ): static {
        return app(static::class, compact('name', 'selector', 'attribute', 'filterSelector'));
    }

    /**
     * Set a callback to be executed after the rule is crawled.
     *
     * @param Closure $callback
     * @return $this
     */
    public function after(Closure $callback): self
    {
        $this->callback = $callback;

        return $this;
    }
}
