<?php

namespace App\Forms\Admin\Traits;

use App\Services\Form\Elements\Input;

trait SlugFormTrait
{
    public static function slugInput(): Input
    {
        return Input::make('slug', 'Slug');
    }
}
