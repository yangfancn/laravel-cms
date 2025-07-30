<?php

namespace App\Forms\Admin;

use App\Services\Form\Elements\Input;
use App\Services\Form\Elements\Select;
use App\Services\Form\Elements\Uploader;
use App\Services\Form\Form;
use App\Services\Form\FormBuilder;
use App\Models\Role;
use Illuminate\Database\Eloquent\Model;
use Inertia\Response;

class UserForm extends FormBuilder
{
    public static function render(
        string $action,
        ?string $title = null,
        string $method = 'POST',
        array|Model|null $data = null
    ): Response {
        $form = new Form($action, $method, $data);

        $roles = Role::pluck('name', 'id')->toArray();
        $form
            ->add(Input::make('email', 'Email')->email()->autofocus())
            ->add(Input::make('name', 'Name'))
            ->add(Uploader::make('photo', 'Photo')->cropper(1))
            ->add(Input::make('password', 'Password')->password())
            ->add(Select::make('roles', 'Roles')->options($roles)->useChips()->multiple());

        return $form->render($title);
    }
}
