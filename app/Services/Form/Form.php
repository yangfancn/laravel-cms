<?php

namespace App\Services\Form;

use App\Models\Traits\Categorizable;
use App\Models\Traits\Metable;
use App\Models\Traits\Taggable;
use App\Services\Form\Elements\Element;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Inertia\Response;

/**
 * Build Form
 */
class Form
{
    use Options;

    protected Collection $fields;

    protected ?string $prefix = null;

    protected bool $disablePrecognitive = false;

    /**
     * @param  'PUT'|'POST'  $method
     * @return void
     */
    public function __construct(
        public string $action,
        public string $method = 'POST',
        public null|Model|Collection|array $data = null
    ) {
        if (! $this->data) {
            $this->data = collect();
        }

        if ($data instanceof Model) {
            // auto load meta relation
            if (
                in_array(Metable::class, class_uses(get_class($data)))
                && ! $this->data->relationLoaded('meta')
            ) {
                $this->data->load('meta');
            }
            // auto load tags relation
            if (in_array(Taggable::class, class_uses(get_class($data)))) {
                $this->data->setRawAttributes($this->data->getAttributes() + ['tags' => $this->data->tags()->pluck('id')->toArray()], true);
            }
        }

        if ($data instanceof Model || $this->data instanceof Collection) {
            $this->data = $this->data->toArray();
        }
    }

    /**
     * gerenrate from data props
     */
    public function create(): array
    {
        return [
            'action' => $this->action,
            'method' => $this->method,
            'fields' => $this->options ? $this->options->map(function (Element|Block $element) {
                return $element->getProperties();
            }) : collect(),
            'data' => $this->data,
            'precognitive' => ! $this->disablePrecognitive,
        ];
    }

    /**
     * disable precognition(禁用表单预测验证)
     */
    public function disablePrecognitive(): self
    {
        $this->disablePrecognitive = true;

        return $this;
    }

    /**
     * render from view
     *
     * @throws \RuntimeException
     */
    public function render(?string $title, string $page = 'DefaultForm'): Response
    {
        return inertia($page, [
            'form' => $this->create(),
            'title' => $title,
        ]);
    }
}
