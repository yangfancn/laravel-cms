<?php

namespace App\Services\Form;

use App\Services\Form\Elements\Element;
use Illuminate\Support\Collection;

/**
 * Block class represents a block of form elements that can be repeated.
 * use for json filed or other array data
 * @package App\Services\Form
 */
class Block
{
    use Options;

    public Collection $fields;

    public object|array $defaultValue;

    protected bool $repeat = false;

    protected ?int $min_repeat_times = null;

    protected ?int $max_repeat_times = null;

    protected bool $reorder = false;

    /**
     * Constructor for the Block class.
     * @param null|string $name filed name
     * @param null|string $title block title(card title)
     * @return void 
     * 
     * example:
     * ```php
     * $block = new Block('seo_meta', 'SEO Meta Tags');
     * $block->add(new Text('title', 'Title'))
     *    ->add(new Text('description', 'Description'))
     *    ->add(
     *         new Block('others', 'Custom Meta Tags')
     *            ->repeater(0, 15, true)
     *           ->add(new Text('name', 'Meta Name'))
     *          ->add(new Text('value', 'Content'))
     *     )
     * ```
     */
    public function __construct(
        public ?string $name = null,
        public ?string $title = null,
    ) {
        $this->defaultValue = new \stdClass;
    }

    /**
     * Enable multiple blocks.
     * @param null|int $min 
     * @param null|int $max 
     * @param bool $reorder 
     * @return \App\Services\Form\Block 
     */
    public function repeater(?int $min = null, ?int $max = null, bool $reorder = false): self
    {
        $this->repeat = true;
        $this->min_repeat_times = $min;
        $this->max_repeat_times = $max;
        $this->reorder = $reorder;
        $this->defaultValue = [];

        return $this;
    }

    /**
     * Get the properties of the block.
     * @return \Illuminate\Support\Collection 
     * @throws \InvalidArgumentException 
     */
    public function getProperties(): Collection
    {
        $properties = collect([
            'name' => $this->name,
            'title' => $this->title,
            'cols' => 12,
            'fields' => $this->options->map(fn (Element|Block $element) => $element->getProperties()),
        ]);

        return $properties->merge($this->repeat ? [
            'field' => 'blocks',
            'repeat' => $this->repeat,
            'min' => $this->min_repeat_times,
            'max' => $this->max_repeat_times,
            'reorder' => $this->reorder,
        ] : [
            'field' => 'block',
        ]);
    }
}
