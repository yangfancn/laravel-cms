<?php

namespace App\Forms\Admin\Traits;

use App\Services\Form\Block;
use App\Services\Form\Elements\Input;

trait MetaFormTrait
{
    public static function metaBlock(): Block
    {
        return (new Block('meta', 'SEO Metas'))
            ->add(Input::make('title', 'Title'))
            ->add(Input::make('keywords', 'Keywords'))
            ->add(Input::make('description', 'Description'))
            ->add(
                (new Block('others', 'Customize Metas'))
                    ->repeater(0, 15, true)
                    ->add(Input::make('name', 'Meta Name'))
                    ->add(Input::make('value', 'Content'))
            );
    }
}
