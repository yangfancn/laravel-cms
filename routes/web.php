<?php

use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\Auth\SessionController;
use App\Http\Controllers\Auth\UserController as AuthUserController;
use App\Http\Controllers\Home\IndexController;
use App\Http\Controllers\Home\SitemapController;
use App\Http\Controllers\Home\SlugController;
use App\Http\Middleware\Admin\ViteBuildDir;
use Illuminate\Support\Facades\Route;

// sessions
Route::middleware(ViteBuildDir::class)->group(function () {
    Route::get('sign-up', [AuthUserController::class, 'create'])->name('sign-up');
    Route::post('sign-up', [AuthUserController::class, 'store'])->name('sign-up')->middleware('throttle:sign-up');
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

Route::get('/', [IndexController::class, 'index'])
    ->name('home');

Route::get('sitemap.xml', SitemapController::class)->name('sitemap');

Route::get('{slug:name}', SlugController::class)
    ->where('slug', '[a-zA-Z0-9\-]+(\/[a-zA-Z0-9\-]+)*')
    ->name('slug');
