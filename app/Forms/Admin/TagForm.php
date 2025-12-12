<?php

namespace App\Forms\Admin;

use App\Models\Tag;
use App\Services\Form\Elements\Input;
use App\Services\Form\Form;
use App\Services\Form\FormBuilder;
use Illuminate\Database\Eloquent\Model;
use Inertia\Response;

class TagForm extends FormBuilder
{
    public static function render(
        string $action,
        ?string $title = null,
        string $method = 'POST',
        array|Model|Tag|null $data = null
    ): Response {
        $form = new Form($action, $method, $data);

        $form
            ->add(Input::make('name', 'Name'))
            ->add(Input::make('slug', 'Slug'));

        return $form->render($title);
    }
}
