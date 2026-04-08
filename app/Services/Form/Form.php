<?php

namespace App\Services\Form;

use App\Services\Form\Elements\Element;
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
        public array $data = []
    ) {}

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
