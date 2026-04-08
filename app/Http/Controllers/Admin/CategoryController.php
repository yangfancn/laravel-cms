<?php

namespace App\Http\Controllers\Admin;

use App\Facades\InertiaMessage;
use App\Forms\Admin\CategoryForm;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CategoryRequest;
use App\Models\Category;
use App\Services\DataTable\Button;
use App\Services\DataTable\DataTable;
use App\Services\DataTable\TextColumn;
use Illuminate\Http\RedirectResponse;
use Inertia\Response;

class CategoryController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Category::class, 'category');
    }

    public function index(Category $category): Response
    {
        $categories = $category->get()->toTree();

        $dataTable = new DataTable(
            flattenTree($categories),
            'Front Categories'
        );

        $dataTable->addColumn(new TextColumn('id', 'ID'))
            ->addColumn(new TextColumn('flatten', 'Name'))
            ->addColumn(new TextColumn('directory', 'Path'))
            ->createAction('admin.categories.create')
            ->addRowAction(
                (new Button('', 'mdi-pencil', 'admin.categories.edit', 'id'))
                    ->flat()
            )
            ->addRowAction(
                (new Button('', 'mdi-delete', 'admin.categories.destroy', 'id'))
                    ->flat()
                    ->color('negative')
                    ->withConfirm('Deleting the parent node will also delete the child nodes and relations(like: posts). Are you sure?')
                    ->method('delete')
            );

        return $dataTable->make();
    }

    public function create(): Response
    {
        return CategoryForm::render(route('admin.categories.store'), 'Create Category');
    }

    public function store(CategoryRequest $request, Category $category): RedirectResponse
    {
        $category->fill($request->all($category->getFillable()))->save();

        InertiaMessage::success(__('messages.createCategorySuccess'));

        return redirect(route('admin.categories.index'));
    }

    public function edit(Category $category): Response
    {
        return CategoryForm::render(
            route('admin.categories.update', $category),
            'Edit Category',
            'PUT',
            $category
        );
    }

    public function update(CategoryRequest $request, Category $category): RedirectResponse
    {
        $category->fill($request->all($category->getFillable()))->save();

        InertiaMessage::success(__('messages.updateCategorySuccess'));

        return redirect()->back();
    }

    public function destroy(Category $category): RedirectResponse
    {
        $category->delete();

        InertiaMessage::warning(__('messages.deleteCategorySuccess'));

        return redirect()->back();
    }
}
