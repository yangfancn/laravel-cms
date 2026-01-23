<?php

namespace App\Forms\Admin;

use App\Enums\CategoryType;
use App\Forms\Admin\Traits\HydrateMetaTrait;
use App\Forms\Admin\Traits\MetaFormTrait;
use App\Models\Category;
use App\Services\Form\Elements\Input;
use App\Services\Form\Elements\Select;
use App\Services\Form\Elements\Toggle;
use App\Services\Form\Form;
use App\Services\Form\FormBuilder;
use Illuminate\Database\Eloquent\Model;

class CategoryForm extends FormBuilder
{
    use HydrateMetaTrait, MetaFormTrait;

    protected static function schema(Form $form): void
    {
        $categories = Category::whereNotIn('id', array_filter([$form->data['id'] ?? null]))
            ->pluck('id', 'name')
            ->all();

        $form->add(Input::make('name', 'Name'))
            ->add(Select::make('parent_id', 'Parent')->options($categories))
            ->add(Input::make('directory', 'Path'))
            ->add(Toggle::make('show', 'Show In Nav')->defaultValue(true))
            ->add(
                Select::make('type', 'Type')
                    ->options(CategoryType::options())
                    ->defaultValue(CategoryType::View->value)
            )
            ->add(Input::make('rank', 'Rank')->number()->defaultValue(0))
            ->add(self::metaBlock());
    }

    protected static function hydrate(Model|Category $model): array
    {
        return [
            ...$model->toArray(),
            ...static::hydrateMeta($model),
        ];
    }
}
