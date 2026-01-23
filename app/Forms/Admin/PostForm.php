<?php

namespace App\Forms\Admin;

use App\Enums\CategoryType;
use App\Forms\Admin\Traits\HydrateMetaTrait;
use App\Forms\Admin\Traits\HydrateSlugTrait;
use App\Forms\Admin\Traits\HydrateTagTrait;
use App\Forms\Admin\Traits\MetaFormTrait;
use App\Forms\Admin\Traits\SlugFormTrait;
use App\Forms\Admin\Traits\TagFormTrait;
use App\Models\Category;
use App\Models\Post;
use App\Services\Form\Elements\DatetimePicker;
use App\Services\Form\Elements\Editor;
use App\Services\Form\Elements\Input;
use App\Services\Form\Elements\Select;
use App\Services\Form\Elements\Uploader;
use App\Services\Form\Form;
use App\Services\Form\FormBuilder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class PostForm extends FormBuilder
{
    use HydrateMetaTrait, MetaFormTrait;
    use HydrateSlugTrait, SlugFormTrait;
    use HydrateTagTrait, TagFormTrait;

    protected static function schema(Form $form): void
    {
        $categories = Category::where('type', CategoryType::Posts->value)->pluck('id', 'name')->all();

        $form->add(Input::make('title', 'Title'))
            ->add(
                Select::make('categories', 'Category')
                    ->options($categories)
                    ->multiple()
                    ->useChips()
            );

        if (! Auth::user()->hasPermissionTo('posts own resource')) {
            $form->add(
                Select::make('user_id', 'User')
                    ->xhrOptionsUrl(route('admin.users.load'))
                    ->useInput()
            );
        }

        $form->add(self::slugInput())
            ->add(Uploader::make('thumb', 'Thumb')->cropper(4 / 3))
            ->add(Input::make('summary', 'Summary')->textarea())
            ->add(self::tagSelect())
            ->add(DatetimePicker::make('created_at', 'Publish Time'))
            ->add(Editor::make('content', 'Content')->counter()->minHeight('20rem'))
            ->add(self::metaBlock());
    }

    protected static function hydrate(Model|Post $model): array
    {
        return [
            ...$model->toArray(),
            ...static::hydrateTag($model),
            ...static::hydrateMeta($model),
            ...static::hydrateSlug($model),
            'thumb' => $model->getFirstMedia('thumb')?->getUrl(), //多文件用 ->getMedia('filedname')->map->getUrl()
            'categories' => $model->categories()->pluck('categories.id')->all(),
        ];
    }
}
