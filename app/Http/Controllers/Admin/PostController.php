<?php

namespace App\Http\Controllers\Admin;

use App\Facades\InertiaMessage;
use App\Forms\Admin\PostForm;
use App\Services\DataTable\Button;
use App\Services\DataTable\DataTable;
use App\Services\DataTable\TextColumn;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\PostRequest;
use App\Models\Post;
use Illuminate\Http\RedirectResponse;
use Inertia\Response;
use Illuminate\Support\Facades\Auth;

class PostController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Post::class, 'post');
    }

    public function index(Post $post): Response
    {
        if (Auth::user()->hasPermissionTo('posts own resource')) {
            $post = $post->where('user_id', Auth::id());
        }

        $table = new DataTable(
            $post->with(['user:id,name', 'category:id,name']),
            'Posts List',
            'title'
        );

        $table
            ->addColumn(new TextColumn('id', 'ID', sortable: true))
            ->addColumn(new TextColumn('title', 'Title'))
            ->addColumn(new TextColumn('category.name', 'Category'))
            ->addColumn(new TextColumn('user.name', 'User'))
            ->addColumn(new TextColumn('created_at', 'Create Time', sortable: true))
            ->createAction('admin.posts.create')
            ->batchDeleteAction('admin.posts.batchDestroy')
            ->addRowAction(
                (new Button('', 'mdi-pencil', 'admin.posts.edit', 'id'))
                    ->flat()
            )
            ->addSelectByRelation('category')
            ->addRowAction(
                (new Button('', 'mdi-delete', 'admin.posts.destroy', 'id'))
                    ->color('negative')
                    ->withConfirm()
                    ->flat()
                    ->method('delete')
            );

        return $table->make();
    }

    public function create(): Response
    {
        return PostForm::render(route('admin.posts.store'), 'Create Post');
    }

    public function store(PostRequest $request, Post $post): RedirectResponse
    {
        $post->fill($request->all($post->getFillable()))->save();

        InertiaMessage::success(__('messages.createPostSuccess'));

        return to_route('admin.posts.index');
    }

    public function edit(Post $post): Response
    {
        return PostForm::render(
            route('admin.posts.update', $post),
            'Edit Post',
            'PUT',
            $post
        );
    }

    public function update(PostRequest $request, Post $post): RedirectResponse
    {
        $post->fill($request->all($post->getFillable()))->save();

        InertiaMessage::success(__('messages.updatePostSuccess'));

        return redirect()->back();
    }

    public function destroy(Post $post): RedirectResponse
    {
        $post->delete();

        InertiaMessage::warning(__('messages.deletePostSuccess'));

        return redirect()->back();
    }

    public function batchDestroy(): RedirectResponse
    {
        return $this->batchDelete(new Post);
    }
}
