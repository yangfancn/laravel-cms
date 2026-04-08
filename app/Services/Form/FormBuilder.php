<?php

namespace App\Services\Form;

use Illuminate\Database\Eloquent\Model;
use Inertia\Response;

abstract class FormBuilder
{
    final public static function render(
        string $action,
        ?string $title = null,
        string $method = 'POST',
        null|Model|array $data = null
    ): Response {
        $form = new Form($action, $method, static::resolveData($data));

        static::schema($form);

        return $form->render($title);
    }

    protected static function resolveData(null|Model|array $data = null): array
    {

        if ($data instanceof Model) {
            return static::hydrate($data);
        }

        return $data ?? [];
    }

    protected static function hydrate(Model $model): array
    {
        return $model->toArray();
    }

    abstract protected static function schema(Form $form): void;
}
