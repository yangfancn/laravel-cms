<?php

namespace App\Http\Controllers\Admin;

use App\Facades\InertiaMessage;
use App\Forms\Admin\PermissionForm;
use App\Services\DataTable\Button;
use App\Services\DataTable\DataTable;
use App\Services\DataTable\TextColumn;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\PermissionRequest;
use App\Models\Permission;
use Illuminate\Http\RedirectResponse;
use Inertia\Response;

class PermissionController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Permission::class, 'permission');
    }

    public function index(Permission $permission): Response
    {
        $table = new DataTable(
            $permission,
            'Permission List',
            'name'
        );

        $table
            ->addColumn(new TextColumn('id', 'ID', sortable: true))
            ->addColumn(new TextColumn('name', 'Name'))
            ->addColumn(new TextColumn('created_at', 'Create Time', sortable: true))
            ->createAction('admin.permissions.create')
            ->batchDeleteAction('permissions.batchDestroy')
            ->addRowAction(
                (new Button('', 'mdi-pencil', 'admin.permissions.edit', 'id'))
                    ->flat()
            )
            ->addRowAction(
                (new Button('', 'mdi-delete', 'admin.permissions.destroy', 'id'))
                    ->color('negative')
                    ->withConfirm()
                    ->flat()
                    ->method('delete')
            );

        return $table->make();
    }

    public function create(): Response
    {
        return PermissionForm::render(route('admin.permissions.store'), 'Create Permission');
    }

    public function store(PermissionRequest $request, Permission $permission): RedirectResponse
    {
        $permission->fill($request->all($permission->getFillable()))->save();

        if ($admin_menu = $request->post('admin_menu')) {
            $permission->adminMenu()->create($admin_menu);
        }

        InertiaMessage::success(__('messages.createPermissionSuccess'));

        return to_route('admin.permissions.index');
    }

    public function edit(Permission $permission): Response
    {
        return PermissionForm::render(
            route('admin.permissions.update', $permission),
            'Create Permission',
            'PUT',
            $permission->load('adminMenu')
        );
    }

    public function update(PermissionRequest $request, Permission $permission): RedirectResponse
    {
        $permission->fill($request->all($permission->getFillable()))->save();

        if (($admin_menu = $request->post('admin_menu')) && isset($admin_menu['label']) && $admin_menu['label']) {
            $permission->adminMenu()->updateOrCreate([], $admin_menu);
        } elseif ($permission->adminMenu) {
            $permission->adminMenu()->delete();
        }

        InertiaMessage::success(__('messages.updatePermissionSuccess'));

        return redirect()->back();
    }

    public function destroy(Permission $permission): RedirectResponse
    {
        $permission->delete();

        InertiaMessage::warning(__('messages.deletePermissionSuccess'));

        return redirect()->back();
    }

    public function batchDestroy(): RedirectResponse
    {
        return $this->batchDelete(new Permission);
    }
}
