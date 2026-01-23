<?php

namespace App\Forms\Admin;

use App\Models\Permission;
use App\Services\Form\Elements\Input;
use App\Services\Form\Elements\Select;
use App\Services\Form\Form;
use App\Services\Form\FormBuilder;
use Illuminate\Database\Eloquent\Model;

class RoleForm extends FormBuilder
{
    protected static function schema(Form $form): void
    {
        $permissions = Permission::all()->pluck('id', 'name')->toArray();

        $form
            ->add(Input::make('name', 'Name'))
            ->add(Select::make('permissions', 'Permissions')->options($permissions)->multiple()->useChips());
    }

    protected static function hydrate(null|Model $data): array
    {
        return [
            ...$data->setVisible(['name'])->toArray(),
            'permissions' => $data->permissions()->pluck('id')->all()
        ];
    }
}
