<?php

use App\Http\Controllers\Api\CommentController;
use App\Http\Controllers\Api\VoteController;
use Illuminate\Support\Facades\Route;

Route::get('/comments', [CommentController::class, 'index']);
Route::get('/comments/{comment}', [CommentController::class, 'show']);
Route::post('/comments', [CommentController::class, 'store']);
Route::post('/vote', [VoteController::class, 'vote']);
