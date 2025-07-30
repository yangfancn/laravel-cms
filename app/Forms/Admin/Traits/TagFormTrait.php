<?php

namespace App\Forms\Admin\Traits;

use App\Services\Form\Elements\Select;

trait TagFormTrait
{
    public static function tagSelect(): Select
    {
        return Select::make('tags', 'Tags')
            ->useChips()
            ->multiple()
            ->xhrOptionsUrl(route('admin.tags.load'))
            ->allowCreate(route('admin.tags.store'));

    }
}
