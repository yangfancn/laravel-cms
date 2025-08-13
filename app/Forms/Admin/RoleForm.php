<?php

namespace App\Forms\Admin;

use App\Services\Form\Elements\Input;
use App\Services\Form\Elements\Select;
use App\Services\Form\Form;
use App\Services\Form\FormBuilder;
use App\Models\Permission;
use App\Models\Role;
use App\Services\Form\Enums\Variant;
use Illuminate\Database\Eloquent\Model;
use Inertia\Response;

class RoleForm extends FormBuilder
{
    public static function render(
        string $action,
        ?string $title = null,
        string $method = 'POST',
        array|Model|Role|null $data = null
    ): Response {
        $form = new Form($action, $method, $data);
        $permissions = Permission::all()->pluck('id', 'name')->toArray();

        $form
            ->add(Input::make('name', 'Name'))
            ->add(Select::make('permissions', 'Permissions')->options($permissions)->multiple()->useChips());

        return $form->render($title);
    }
}
