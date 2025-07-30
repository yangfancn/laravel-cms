<?php

use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\Auth\SessionController;
use App\Http\Controllers\Home\CategoryController;
use App\Http\Controllers\Home\IndexController;
use App\Http\Controllers\Home\PostController;
use App\Http\Middleware\Admin\ViteBuildDir;
use App\Models\Post;
use Illuminate\Support\Facades\Route;

// sessions
Route::middleware(ViteBuildDir::class)->group(function () {
    Route::get('login', [SessionController::class, 'create'])->name('login');
    Route::post('login', [SessionController::class, 'store'])->name('login');
    Route::delete('logout', [SessionController::class, 'destroy'])->name('logout');
    Route::get('password/confirm', [PasswordController::class, 'showConfirmPasswordForm'])
        ->middleware('auth')
        ->name('password.confirm');
    Route::post('password/confirm', [PasswordController::class, 'confirmPassword'])
        ->middleware(['auth', 'throttle:6,1'])
        ->name('password.confirm.process');
    Route::middleware('guest')->group(function () {
        Route::get('password/reset', [PasswordController::class, 'showRequestForm'])->name('password.request');
        Route::post('password/email', [PasswordController::class, 'sendResetLink'])->name('password.email');
        Route::get('password/reset/{token}', [PasswordController::class, 'showResetForm'])->name('password.reset');
        Route::post('password/reset', [PasswordController::class, 'reset'])->name('password.update');
    });
});

Route::get('/', [IndexController::class, 'index'])->name('home');

Route::get('/search', [PostController::class, 'index']);

Route::get('{category:full_path}', [CategoryController::class, 'show'])
    ->where('category', '([a-zA-Z0-9-]+)*[a-zA-Z0-9-]+')
    ->name('categories.show');

Route::get('/post/{post}', fn (Post $post) => redirect($post->uri, 301));

Route::get('/{post:slug}.html', [PostController::class, 'show'])
    ->where(['slug' => '[a-zA-Z]+[0-9]?[a-zA-Z]+'])
    ->name('posts.show');
