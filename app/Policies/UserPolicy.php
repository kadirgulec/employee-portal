<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\Response;

class UserPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return auth()->user()->can('backend.users.view-any') || true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, User $model): bool
    {
        return auth()->user()->can('backend.users.view') || $user->id === $model->id;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return auth()->user()->can('backend.users.create');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, User $model): bool
    {
        return auth()->user()->can('backend.users.update') || $user->id === $model->id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, User $model): bool
    {
        return auth()->user()->can('backend.users.delete') && $user->id != $model->id;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, User $model): bool
    {
        return auth()->user()->can('backend.users.restore');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, User $model): bool
    {
//        return auth()->user()->can('backend.users.force-delete');
        return false;
    }

    public function replicateUser(User $user): bool
    {
        return auth()->user()->can('backend.users.replicate');
    }

    public function reorderUser(User $user): bool
    {
        return auth()->user()->can('backend.users.reorder');
    }
}
