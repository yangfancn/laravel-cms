<?php

namespace App\Http\Controllers\Admin;

use App\Facades\InertiaMessage;
use App\Forms\Admin\TagForm;
use App\Services\DataTable\Button;
use App\Services\DataTable\DataTable;
use App\Services\DataTable\TextColumn;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\TagRequest;
use App\Models\Tag;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Inertia\Response;

class TagController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Tag::class, 'tag');
    }

    public function index(Tag $tag): Response
    {
        $table = new DataTable($tag, 'Tag List', 'name');
        $table->addColumn(new TextColumn('id', 'ID', sortable: true))
            ->addColumn(new TextColumn('name', 'Name'))
            ->addColumn(new TextColumn('slug', 'SLUG'))
            ->addColumn(new TextColumn('created_at', 'Create Time', sortable: true))
            ->createAction('admin.tags.create')
            ->addRowAction(
                (new Button('', 'mdi-pencil', 'admin.tags.edit', 'id'))
                    ->flat()
            )
            ->addRowAction(
                (new Button('', 'mdi-delete', 'admin.tags.destroy', 'id'))
                    ->color('negative')
                    ->withConfirm()
                    ->flat()
                    ->method('delete')
            );

        return $table->make();
    }

    public function create(): Response
    {
        return (new TagForm)->render(route('admin.tags.store'), 'Create Tag');
    }

    public function store(TagRequest $request, Tag $tag): RedirectResponse|JsonResponse
    {
        $tag->fill($request->validated())->save();

        if ($request->acceptsJson()) {
            return new JsonResponse([
                'label' => $tag->name,
                'value' => $tag->id,
            ]);
        } else {
            InertiaMessage::success(__('messages.createTagSuccess'));

            return redirect(route('admin.tags.index'));
        }

    }

    public function edit(Tag $tag): Response
    {
        return (new TagForm)->render(route('admin.tags.update', $tag), 'Edit Tag', 'PUT', $tag);
    }

    public function update(TagRequest $request, Tag $tag): RedirectResponse
    {
        $tag->fill($request->validated())->save();

        InertiaMessage::success(__('messages.updateTagSuccess'));

        return redirect()->back();
    }

    public function destroy(Tag $tag): RedirectResponse
    {
        $tag->delete();

        InertiaMessage::warning(__('messages.deleteTagSuccess'));

        return redirect()->back();
    }

    public function load(): JsonResponse
    {
        return $this->selectOptionsLoad(new Tag);
    }
}
