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
    use MetaFormTrait, HydrateMetaTrait;

    protected static function schema(Form $form): void
    {
        $form
            ->add(Input::make('name', 'Name'))
            ->add(self::metaBlock());
    }

    protected static function hydrate(null|Model $data): array
    {
        return [
            ...$data->toArray(),
            ...self::hydrateMeta($data)
        ];
    }
}
