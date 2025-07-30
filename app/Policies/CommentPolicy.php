<?php

namespace App\Policies;

use App\Models\Comment;
use App\Models\User;

class CommentPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return $user->hasPermissionTo('comments create');
    }

    public function update(User $user, Comment $model): bool
    {
        if ($user->hasPermissionTo('comments update')) {
            return ! $user->hasPermissionTo('comments own resource') || $model->user_id === $user->id;
        }

        return false;
    }

    public function delete(User $user, Comment $model): bool
    {
        if ($user->hasPermissionTo('comments delete')) {
            return ! $user->hasPermissionTo('comments own resource') || $model->user_id === $user->id;
        }

        return false;
    }
}
