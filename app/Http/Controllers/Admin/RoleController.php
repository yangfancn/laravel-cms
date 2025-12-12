<?php

namespace App\Http\Controllers\Admin;

use App\Facades\InertiaMessage;
use App\Forms\Admin\RoleForm;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\RoleRequest;
use App\Models\Role;
use App\Services\DataTable\Button;
use App\Services\DataTable\DataTable;
use App\Services\DataTable\TextColumn;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Response;

class RoleController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Role::class, 'role');
    }

    public function index(Request $request, Role $role): Response
    {
        $table = new DataTable(
            $role,
            'Roles List',
            'name'
        );

        $table
            ->addColumn(new TextColumn('id', 'ID', sortable: true))
            ->addColumn(new TextColumn('name', 'Name'))
            ->addColumn(new TextColumn('created_at', 'Create Time', sortable: true))
            ->createAction('admin.roles.create')
            ->addRowAction(
                (new Button('', 'mdi-pencil', 'admin.roles.edit', 'id'))
                    ->flat()
            )
            ->addRowAction(
                (new Button('', 'mdi-delete', 'admin.roles.destroy', 'id'))
                    ->color('negative')
                    ->withConfirm()
                    ->flat()
                    ->method('delete')
            );

        return $table->make();
    }

    public function create(): Response
    {
        return RoleForm::render(route('admin.roles.store'), 'Create Role');
    }

    public function store(RoleRequest $request, Role $role): RedirectResponse
    {
        $role->fill($request->all($role->getFillable()))->save();

        $role->permissions()->sync($request->post('permissions'));

        InertiaMessage::success(__('messages.createRoleSuccess'));

        return to_route('admin.roles.index');
    }

    public function edit(Role $role): Response
    {
        $data = $role->setVisible(['name'])->toArray();
        $data['permissions'] = $role->permissions->pluck('id')->values()->all();

        return RoleForm::render(route('admin.roles.update', $role), 'Edite Role', 'PUT', $data);
    }

    public function update(RoleRequest $request, Role $role): RedirectResponse
    {
        $role->fill($request->all($role->getFillable()))->save();
        $role->permissions()->sync($request->post('permissions'));

        InertiaMessage::success(__('messages.updateRoleSuccess'));

        return redirect()->back();
    }

    public function destroy(Role $role): RedirectResponse
    {
        $role->delete();

        InertiaMessage::warning(__('messages.deleteRoleSuccess'));

        return redirect()->back();
    }
}
