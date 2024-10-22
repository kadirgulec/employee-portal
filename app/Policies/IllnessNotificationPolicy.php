<?php

namespace App\Policies;

use App\Models\IllnessNotification;
use App\Models\User;

class IllnessNotificationPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return auth()->user()->can('backend.illness-notifications.view-any');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, IllnessNotification $illnessNotification): bool
    {
        return auth()->user()->can('backend.illness-notifications.view') || $user->id === $illnessNotification->user->id ;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return auth()->user()->can('backend.illness-notifications.create');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user): bool
    {
        return auth()->user()->can('backend.illness-notifications.update');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user): bool
    {
        return auth()->user()->can('backend.illness-notifications.delete');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user): bool
    {
        return auth()->user()->can('backend.illness-notifications.restore');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user): bool
    {
//        return auth()->user()->can('backend.illness-notifications.force-delete');
        return false;
    }

    public function replicateUser(User $user) :bool
    {
        return auth()->user()->can('backend.illness-notifications.replicate');
    }

    public function reorderUser(User $user) :bool
    {
        return auth()->user()->can('backend.illness-notifications.reorder');
    }
}
