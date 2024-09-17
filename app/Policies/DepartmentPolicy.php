<?php

namespace App\Policies;

use App\Models\Department;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class DepartmentPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return auth()->user()->can('backend.departments.view-any');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Department $department): bool
    {
        return auth()->user()->can('backend.departments.view');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return auth()->user()->can('backend.departments.create');

    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Department $department): bool
    {
        return auth()->user()->can('backend.departments.update');

    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Department $department): bool
    {
        return auth()->user()->can('backend.departments.delete');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Department $department): bool
    {
        return auth()->user()->can('backend.departments.restore');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Department $department): bool
    {
        return auth()->user()->can('backend.departments.force-delete');
    }
    public function replicateUser(User $user) :bool
    {
        return auth()->user()->can('backend.departments.replicate');
    }

    public function reorderUser(User $user) :bool
    {
        return auth()->user()->can('backend.departments.reorder');
    }
}
