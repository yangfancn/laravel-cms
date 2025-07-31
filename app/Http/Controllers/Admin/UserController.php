<?php

namespace App\Http\Controllers\Admin;

use App\Facades\InertiaMessage;
use App\Forms\Admin\UserForm;
use App\Services\DataTable\Button;
use App\Services\DataTable\ChipsColumn;
use App\Services\DataTable\DataTable;
use App\Services\DataTable\enums\Align;
use App\Services\DataTable\TextColumn;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UserRequest;
use App\Http\Resources\Admin\UserCollection;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Response;

class UserController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(User::class, 'user');
        //        $this->middleware('password.confirm');
    }

    protected function resourceAbilityMap(): array
    {
        return array_merge(parent::resourceAbilityMap(), [
            'load' => 'load',
        ]);
    }

    protected function resourceMethodsWithoutModels(): array
    {
        return array_merge(parent::resourceMethodsWithoutModels(), ['load']);
    }

    public function index(Request $request, User $user): Response
    {
        if (\Auth::user()->hasPermissionTo('users own resource')) {
            $user = $user->where('id', \Auth::id());
        }

        $table = new DataTable(
            $user->with('roles'),
            'Users List',
            'name',
            UserCollection::class
        );

        $table
            ->addColumn(new TextColumn('id', 'ID', sortable: true))
            ->addColumn(new TextColumn('name', 'Name'))
            ->addColumn(new ChipsColumn('roles', 'Roles', Align::CENTER))
            ->addColumn(new TextColumn('created_at', 'Create Time', sortable: true))
            ->createAction('admin.users.create')
            ->batchDeleteAction('admin.users.batchDestroy')
            ->addRowAction(
                (new Button('', 'mdi-pencil', 'admin.users.edit', 'id'))
                    ->flat()
            )
            ->addRowAction(
                (new Button('', 'mdi-delete', 'admin.users.destroy', 'id'))
                    ->color('negative')
                    ->withConfirm()
                    ->flat()
                    ->method('delete')
            );

        return $table->make();
    }

    public function create(): Response
    {
        return UserForm::render(route('admin.users.store'), 'Create User');
    }

    public function store(UserRequest $request, User $user): RedirectResponse
    {
        $user->fill($request->all($user->getFillable()))->save();
        $user->roles()->sync($request->post('roles'));

        InertiaMessage::success(__('messages.createUserSuccess'));

        return to_route('admin.users.index');
    }

    public function edit(User $user): Response
    {
        $data = $user->setVisible(['name', 'email', 'photo'])->toArray();
        $data['roles'] = $user->roles->pluck('id')->values()->all();

        return UserForm::render(
            route('admin.users.update', $user),
            'Edit User',
            'PUT',
            $data
        );
    }

    public function update(UserRequest $request, User $user): RedirectResponse
    {
        $user->fill($request->only($user->getFillable()))->save();
        $user->roles()->sync($request->post('roles'));

        InertiaMessage::success(__('messages.updateUserSuccess'));

        return redirect()->back();
    }

    public function destroy(User $user): RedirectResponse
    {
        $user->delete();

        InertiaMessage::warning(__('messages.deleteUserSuccess'));

        return redirect()->back();
    }

    public function batchDestroy(): RedirectResponse
    {
        return $this->batchDelete(new User);
    }

    public function load(): JsonResponse
    {
        return $this->selectOptionsLoad(new User);
    }
}
