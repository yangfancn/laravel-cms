<?php

namespace App\Forms\Admin;

use App\Forms\Admin\Traits\MetaFormTrait;
use App\Services\Form\Elements\Input;
use App\Services\Form\Form;
use App\Services\Form\FormBuilder;
use App\Models\Site;
use Illuminate\Database\Eloquent\Model;
use Inertia\Response;

class SiteForm extends FormBuilder
{
    use MetaFormTrait;

    public static function render(
        string $action,
        ?string $title = null,
        string $method = 'POST',
        array|Model|Site|null $data = null
    ): Response {
        $form = new Form($action, $method, $data);

        $form
            ->add(Input::make('name', 'Name'))
            ->add(self::metaBlock());

        return $form->render($title);
    }
}
