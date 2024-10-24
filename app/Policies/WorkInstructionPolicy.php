<?php

namespace App\Policies;

use App\Models\User;
use App\Models\WorkInstruction;

class WorkInstructionPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return auth()->user()->can('backend.work-instructions.view-any');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, WorkInstruction $workInstruction): bool
    {
        return auth()->user()->can('backend.work-instructions.view');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return auth()->user()->can('backend.work-instructions.create');

    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, WorkInstruction $workInstruction): bool
    {
        return auth()->user()->can('backend.work-instructions.update');

    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, WorkInstruction $workInstruction): bool
    {
        return auth()->user()->can('backend.work-instructions.delete');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, WorkInstruction $workInstruction): bool
    {
        return auth()->user()->can('backend.work-instructions.restore');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, WorkInstruction $workInstruction): bool
    {
//        return auth()->user()->can('backend.work-instructions.force-delete');
        return false;
    }

    public function replicateUser(User $user): bool
    {
        return auth()->user()->can('backend.work-instructions.replicate');
    }

    public function reorderUser(User $user): bool
    {
        return auth()->user()->can('backend.work-instructions.reorder');
    }
}
