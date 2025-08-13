<?php

namespace App\Forms\Admin;

use App\Services\Form\Block;
use App\Services\Form\Elements\ColorPicker;
use App\Services\Form\Elements\Input;
use App\Services\Form\Elements\Select;
use App\Services\Form\Form;
use App\Services\Form\FormBuilder;
use App\Models\AdminMenu;
use App\Models\Permission;
use Illuminate\Database\Eloquent\Model;
use Inertia\Response;

class PermissionForm extends FormBuilder
{
    public static function render(
        string $action,
        ?string $title = null,
        string $method = 'POST',
        Model|Permission|array|null $data = null
    ): Response {
        $form = new Form($action, $method, $data);

        $parents = AdminMenu::pluck('id', 'label')->toArray();

        $form->add(Input::make('name', 'Name'))
            ->add(
                (new Block('admin_menu', 'For Admin Menu(nullable)'))
                    ->add(Input::make('label', 'Label (also lang menu key)'))
                    ->add(Input::make('route', 'Route'))
                    ->add(
                        (new Block('route_params', 'Route Params(nullable)'))
                            ->repeater(0, 3)
                            ->add(Input::make('name', 'Name'))
                            ->add(Input::make('value', 'Value'))
                    )
                    ->add(Input::make('icon', 'MDI icon name'))
                    ->add(ColorPicker::make('icon_color', 'MDI icon color'))
                    ->add(Select::make('parent_id', 'Parent')->options($parents)->clearable())
            );

        return $form->render($title);
    }
}
