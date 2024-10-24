<?php

namespace App\Policies;

use App\Models\Group;
use App\Models\User;

class GroupPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return auth()->user()->can('backend.groups.view-any');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Group $group): bool
    {
        return auth()->user()->can('backend.groups.view');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return auth()->user()->can('backend.groups.create');

    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Group $group): bool
    {
        return auth()->user()->can('backend.groups.update');

    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Group $group): bool
    {
        return auth()->user()->can('backend.groups.delete');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Group $group): bool
    {
        return auth()->user()->can('backend.groups.restore');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Group $group): bool
    {
//        return auth()->user()->can('backend.groups.force-delete');
        return false;
    }
    public function replicateUser(User $user) :bool
    {
        return auth()->user()->can('backend.groups.replicate');
    }

    public function reorderUser(User $user) :bool
    {
        return auth()->user()->can('backend.groups.reorder');
    }
}
