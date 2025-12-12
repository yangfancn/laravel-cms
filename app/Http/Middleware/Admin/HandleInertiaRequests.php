<?php

namespace App\Http\Middleware\Admin;

use App\Models\AdminMenu;
use App\Services\InertiaMessage\InertiaMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that's loaded on the first page visit.
     *
     * @see https://inertiajs.com/server-side-setup#root-template
     *
     * @var string
     */
    protected $rootView = 'admin::app';

    /**
     * Determines the current asset version.
     *
     * @see https://inertiajs.com/asset-versioning
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @see https://inertiajs.com/shared-data
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        $data = parent::share($request);
        if ($user = clone Auth::user()) {
            $data += [
                'user' => $user->load('roles:name')->setVisible(['id', 'name', 'photo', 'roles']),
                'menu' => AdminMenu::with('permission')
                    ->get()
                    ->append('params')
                    ->filter(fn (AdminMenu $menu) => Auth::user()->can($menu->permission->name))
                    ->toTree(),
            ];
        }

        return array_merge($data, [
            'inertiaNotify' => function () {
                return app(InertiaMessage::class)->getMessages();
            },
        ]);
    }
}
