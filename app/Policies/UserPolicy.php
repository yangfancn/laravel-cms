<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('users viewAny');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, User $model): false
    {
        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasPermissionTo('users create');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, User $model): bool
    {
        if ($user->hasPermissionTo('users update')) {
            return ! $user->hasPermissionTo('users own resource') || $model->id === $user->id;
        }

        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, User $model): bool
    {
        if ($user->hasPermissionTo('users delete')) {
            return ! $user->hasPermissionTo('users own resource') || $model->id === $user->id;
        }

        return false;
    }

    public function load(User $user): bool
    {
        return $user->hasAnyPermission(['posts create', 'posts update']) && ! $user->hasPermissionTo('posts own resource');
    }
}
