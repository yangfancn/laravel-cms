<?php

namespace App\Forms\Admin;

use App\Models\AdminMenu;
use App\Services\Form\Block;
use App\Services\Form\Elements\ColorPicker;
use App\Services\Form\Elements\Input;
use App\Services\Form\Elements\Select;
use App\Services\Form\Form;
use App\Services\Form\FormBuilder;
use Illuminate\Database\Eloquent\Model;

class PermissionForm extends FormBuilder
{
    protected static function schema(Form $form): void
    {
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
    }

    protected static function resolveData(null|Model|array $data = null): array
    {
        return [
            ...$data->toArray(),
            'admin_menu' => $data->adminMenu?->toArray()
        ];
    }
}
