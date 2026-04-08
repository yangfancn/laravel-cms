<?php

namespace App\Policies;

use App\Models\Post;
use App\Models\User;

class PostPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('posts viewAny');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Post $model): false
    {
        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasPermissionTo('posts create');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Post $model): bool
    {
        if ($user->hasPermissionTo('posts update')) {
            return ! $user->hasPermissionTo('posts own resource') || $model->user_id === $user->id;
        }

        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Post $model): bool
    {
        if ($user->hasPermissionTo('posts delete')) {
            return ! $user->hasPermissionTo('posts own resource') || $model->user_id === $user->id;
        }

        return false;
    }
}
