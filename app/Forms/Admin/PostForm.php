<?php

namespace App\Forms\Admin;

use App\Forms\Admin\Traits\MetaFormTrait;
use App\Forms\Admin\Traits\TagFormTrait;
use App\Services\Form\Elements\DatetimePicker;
use App\Services\Form\Elements\Editor;
use App\Services\Form\Elements\Input;
use App\Services\Form\Elements\Select;
use App\Services\Form\Elements\Uploader;
use App\Services\Form\Form;
use App\Services\Form\FormBuilder;
use App\Models\Category;
use App\Models\Post;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Inertia\Response;

class PostForm extends FormBuilder
{
    use MetaFormTrait, TagFormTrait;

    public static function render(
        string $action,
        ?string $title = null,
        string $method = 'POST',
        Model|Post|array|null $data = null
    ): Response {
        $form = new Form($action, $method, $data);
        $categories = Category::where('type', 1)->pluck('id', 'name')->all();
        $form->add(Input::make('title', 'Title'))
            ->add(Select::make('category_id', 'Category')->options($categories));

        if (! Auth::user()->hasPermissionTo('posts own resource')) {
            $form->add(
                Select::make('user_id', 'User')
                    ->xhrOptionsUrl(route('admin.users.load'))
                    ->useInput()
            );
        }

        $form->add(Uploader::make('thumb', 'Thumb'))
            ->add(Input::make('summary', 'Summary')->textarea())
            ->add(self::tagSelect())
            ->add(DatetimePicker::make('created_at', 'Publish Time'))
            ->add(Editor::make('content', 'Content')->counter()->minHeight('20rem'))
            ->add(self::metaBlock());

        return $form->render($title);
    }
}
