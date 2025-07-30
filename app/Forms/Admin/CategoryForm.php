<?php

namespace App\Forms\Admin;

use App\Forms\Admin\Traits\MetaFormTrait;
use App\Services\Form\Elements\Input;
use App\Services\Form\Elements\Select;
use App\Services\Form\Elements\Toggle;
use App\Services\Form\Form;
use App\Services\Form\FormBuilder;
use App\Models\Category;
use Illuminate\Database\Eloquent\Model;
use Inertia\Response;

class CategoryForm extends FormBuilder
{
    use MetaFormTrait;

    public static function render(
        string $action,
        ?string $title = null,
        string $method = 'POST',
        Model|Category|array|null $data = null
    ): Response {
        $form = new Form($action, $method, $data);
        $categories = Category::whereNotIn('id', $data ? [$data['id']] : [])->pluck('name', 'id')->all();
        $form->add(Input::make('name', 'Name'))
            ->add(Select::make('parent_id', 'Parent')->options($categories))
            ->add(Input::make('directory', 'Path'))
            ->add(Input::make('route', 'Route'))
            ->add(Toggle::make('show', 'Show In Nav')->defaultValue(true))
            ->add(Select::make('type', 'Type')->options([
                1 => 'Articles List',
                0 => 'Single Page',
            ])->defaultValue(1))
            ->add(Input::make('rank', 'Rank')->number()->defaultValue(0))
            ->add(self::metaBlock());

        return $form->render($title);
    }
}
