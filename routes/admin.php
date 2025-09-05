<?php

use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\CommentController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\FileController;
use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\Admin\PostController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\SiteController;
use App\Http\Controllers\Admin\TagController;
use App\Http\Controllers\Admin\UserController;
use Illuminate\Support\Facades\Route;

Route::post('upload', [FileController::class, 'store'])->name('upload');

Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

// append resource route
Route::delete('users', [UserController::class, 'batchDestroy'])->name('users.batchDestroy');
Route::get('users/load', [UserController::class, 'load'])->name('users.load');
Route::get('tags/load', [TagController::class, 'load'])->name('tags.load');
Route::delete('permissions', [PermissionController::class, 'batchDestroy'])->name('permissions.batchDestroy');
Route::delete('posts', [PostController::class, 'batchDestroy'])->name('posts.batchDestroy');

Route::resources([
    'permissions' => PermissionController::class,
    'roles' => RoleController::class,
    'users' => UserController::class,
    'categories' => CategoryController::class,
    'posts' => PostController::class,
    'tags' => TagController::class,
], [
    'except' => ['show'],
]);

Route::delete('comments', [CommentController::class, 'batchDestroy'])->name('comments.batchDestroy');
Route::put('comments/{comment}/approve', [CommentController::class, 'approve'])->name('comments.approve');
Route::resource('comments', CommentController::class)->except(['show', 'create', 'store']);

// sites 只能对第一条数据更新,不能新增/删除
// Route::get('/site', [SiteController::class, 'edit'])->name('sites.edit');
// Route::put('/site', [SiteController::class, 'update'])->name('sites.update');

Route::resource('sites', SiteController::class)->only(['edit', 'update']);
