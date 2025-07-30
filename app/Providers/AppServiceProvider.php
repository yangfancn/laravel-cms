<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        View::addNamespace('admin', resource_path('admin/views'));
        View::addNamespace('home', resource_path('home/views'));

        // superAdmin 拥有所有权限 && 防止误删初始用户(会存在一点点性能损耗，也可以直接在 UserController@destroy,batchDestroy 中判断或者模型事件)
        Gate::before(fn (User $user, string $ability, mixed $args) => $user->hasRole('super admin') && ! ($args && $args[0] instanceof User && $args[0]->id === 1 && $ability === 'delete') ? true : null);
    }
}
