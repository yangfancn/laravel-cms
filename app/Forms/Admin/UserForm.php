<?php

namespace App\Forms\Admin;

use App\Models\Role;
use App\Models\User;
use App\Services\Form\Elements\Input;
use App\Services\Form\Elements\Select;
use App\Services\Form\Elements\Uploader;
use App\Services\Form\Form;
use App\Services\Form\FormBuilder;
use Illuminate\Database\Eloquent\Model;

class UserForm extends FormBuilder
{
    protected static function schema(Form $form): void
    {
        $roles = Role::pluck('id', 'name')->toArray();
        $form
            ->add(Input::make('email', 'Email')->email()->autofocus())
            ->add(Input::make('name', 'Name'))
            ->add(Uploader::make('avatar', 'Avatar')->cropper(1))
            ->add(Input::make('password', 'Password')->password())
            ->add(Select::make('roles', 'Roles')->options($roles)->useChips()->multiple());
    }

    protected static function hydrate(Model|User $model): array
    {
        return [
            ...$model->only(['email', 'name']),
            'roles' => $model->roles()->pluck('id')->all(),
            'avatar' => $model->getFirstMedia('avatar')?->getUrl(),
        ];
    }
}
