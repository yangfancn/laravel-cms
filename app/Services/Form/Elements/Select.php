<?php

namespace App\Services\Form\Elements;

use App\Services\Form\Elements\Traits\Clearable;
use App\Services\Form\Elements\Traits\Counter;
use App\Services\Form\Elements\Traits\HasAffixes;
use App\Services\Form\Elements\Traits\Multiple;
use App\Services\Form\Elements\Traits\Styles;
use App\Services\Form\Elements\Traits\UseChips;
use App\Services\Form\Elements\Traits\UseInput;

/**
 * Select class represents a select dropdown form element.
 * It supports features like multiple selection, clearable options, affixes, and xhr loading of
 * @package App\Services\Form\Elements
 */
class Select extends Element
{
    use Clearable, Counter, HasAffixes, Multiple, Styles, UseChips, UseInput;

    protected string $field = 'select';

    protected bool $emitValue = true;

    protected bool $mapOptions = true;

    protected ?string $newValueMode;

    protected ?int $maxLength = null;

    protected array $options = [];

    protected ?string $xhrOptionsUrl = null;

    protected ?string $xhrCreateOptionUrl = null;

    protected string $dropdownIcon = 'arrow_drop_down';

    /**
     * Set the options for the select element.
     * @param array $options example: ['value', 'value'] or [label => value ,...] or ['group1' => [label => value,...], ...]
     * @return \App\Services\Form\Elements\Select
     */
    public function options(array $options): self
    {

        $this->options = self::makeOptions($options);

        return $this;
    }

    /**
     * Set max selected options length.
     * @param int $length
     * @return \App\Services\Form\Elements\Select
     */
    public function maxLength(int $length): self
    {
        $this->maxLength = $length;

        return $this;
    }

   /**
    * Set xhr request url
    * @param string $url
    * @return \App\Services\Form\Elements\Select
    */
    public function xhrOptionsUrl(string $url): self
    {
        $this->xhrOptionsUrl = $url;

        return $this;
    }

    /**
     * Set the icon for the dropdown.
     * @param string $icon example: 'arrow_drop_down', 'expand_more' or any other mdi-
     * icon name
     * @return \App\Services\Form\Elements\Select
     */
    public function dropdownIcon(string $icon): self
    {
        $this->dropdownIcon = $icon;

        return $this;
    }

    /**
     * Allow creating new options via an XHR request.
     * @param string $url
     * @return \App\Services\Form\Elements\Select
     */
    public function allowCreate(?string $url = null): self
    {
        $this->newValueMode = 'add-unique';
        $this->xhrCreateOptionUrl = $url;
        $this->useInput();
        $this->useChips();

        return $this;
    }

    /**
     * Generate options array from the provided options.
     * @param array $options
     * @return array
     */
    public static function makeOptions(array $options): array
    {
        return collect($options)->flatMap(function ($item, $key) {
            if (is_array($item)) {
                return [
                    ['label' => $key, 'header' => true, 'disable' => true],
                    ...self::makeOptions($item),
                ];
            } else {
                return [['label' => $key, 'value' => $item]];
            }
        })->all();
    }
}
