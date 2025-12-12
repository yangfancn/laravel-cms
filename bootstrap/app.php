<?php

use App\Facades\InertiaMessage;
use App\Http\Middleware\Admin\HandleInertiaRequests;
use App\Http\Middleware\Admin\ViteBuildDir;
use App\Http\Middleware\EnsurePrecognitionApplies;
use App\Http\Middleware\FilterEmptyFields;
use App\Http\Middleware\Home\CommonData;
use App\Http\Middleware\Home\TrackVisitor;
use App\Services\InertiaMessage\Enums\Position;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Http\Exceptions\PostTooLargeException;
use Illuminate\Http\Request;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Support\Facades\Route;
use Spatie\Permission\Middleware\PermissionMiddleware;
use Spatie\Permission\Middleware\RoleMiddleware;
use Spatie\Permission\Middleware\RoleOrPermissionMiddleware;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        using: function () {
            Route::middleware('admin')
                ->prefix('manager')
                ->name('admin.')
                ->group(base_path('routes/admin.php'));

            Route::middleware('web')
                ->group(base_path('routes/web.php'));

            Route::middleware('api')
                ->prefix('api')
                ->group(base_path('routes/api.php'));
        },
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // default login route
        $middleware->redirectGuestsTo(fn (Request $request) => route('login'));

        // alias
        $middleware->alias([
            'role' => RoleMiddleware::class,
            'permission' => PermissionMiddleware::class,
            'rolesOrPermissions' => RoleOrPermissionMiddleware::class,
        ]);

        // admin middlewares
        $middleware->group('admin', array_merge($middleware->getMiddlewareGroups()['web'], [
            ViteBuildDir::class,
            'auth',
            'auth.session',
            FilterEmptyFields::class,
            HandleInertiaRequests::class,
            EnsurePrecognitionApplies::class,
            'role:admin',
        ]));

        $middleware->web(append: [
            \App\Http\Middleware\Home\HandleInertiaRequests::class,
            TrackVisitor::class,
            \App\Http\Middleware\Home\ViteBuildDir::class,
            CommonData::class,
        ]);

        $middleware->api(append: [
            EncryptCookies::class,
            AddQueuedCookiesToResponse::class,
            StartSession::class,
            VerifyCsrfToken::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->render(function (PostTooLargeException $e, Request $request) {
            $msg = __('messages.exceedsAllowSize', [
                'size' => ini_get('upload_max_filesize'),
            ]);

            // 普通表单场景
            return back()
                ->withErrors(['file' => $msg])
                ->withInput()
                ->setStatusCode(422);
        });

        $exceptions->respond(function (SymfonyResponse $response, Throwable $e, Request $request) {
            $status = $response->getStatusCode();

            if (app()->isProduction() && in_array($status, [400, 401, 403, 404, 500])) {
                $message = __('errors.'.$status) ?: '';

                if ($request->hasHeader('X-inertia')) {
                    InertiaMessage::error($message, position: Position::CENTER);

                    return redirect()->back();
                }
            }

            //            if ($response->getStatusCode() === 419) {
            //                return back()->with(['error' => $message]);
            //            }

            return $response;
        });
    })->create();
