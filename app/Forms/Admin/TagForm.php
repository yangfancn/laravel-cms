<?php

namespace App\Forms\Admin;

use App\Forms\Admin\Traits\HydrateSlugTrait;
use App\Forms\Admin\Traits\SlugFormTrait;
use App\Services\Form\Elements\Input;
use App\Services\Form\Form;
use App\Services\Form\FormBuilder;
use Illuminate\Database\Eloquent\Model;

class TagForm extends FormBuilder
{
    use HydrateSlugTrait, SlugFormTrait;

    protected static function schema(Form $form): void
    {
        $form
            ->add(Input::make('name', 'Name'))
            ->add(self::slugInput());
    }

    protected static function hydrate(?Model $model): array
    {
        return [
            ...$model->toArray(),
            ...static::hydrateSlug($model),
        ];
    }
}
