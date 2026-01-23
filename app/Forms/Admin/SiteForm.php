<?php

namespace App\Forms\Admin;

use App\Forms\Admin\Traits\HydrateMetaTrait;
use App\Forms\Admin\Traits\MetaFormTrait;
use App\Services\Form\Elements\Input;
use App\Services\Form\Form;
use App\Services\Form\FormBuilder;
use Illuminate\Database\Eloquent\Model;

class SiteForm extends FormBuilder
{
    use HydrateMetaTrait, MetaFormTrait;

    protected static function schema(Form $form): void
    {
        $form
            ->add(Input::make('name', 'Name'))
            ->add(self::metaBlock());
    }

    protected static function hydrate(?Model $data): array
    {
        return [
            ...$data->toArray(),
            ...self::hydrateMeta($data),
        ];
    }
}
