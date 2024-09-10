<?php

namespace App\Policies;

use App\Models\SPProduct;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class SPProductPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return auth()->user()->can('view-any SPProduct');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, SPProduct $sPProduct): bool
    {
        return auth()->user()->can('view SPProduct');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return auth()->user()->can('create SPProduct');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, SPProduct $sPProduct): bool
    {
        return auth()->user()->can('update SPProduct');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, SPProduct $sPProduct): bool
    {
        return auth()->user()->can('delete SPProduct');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, SPProduct $sPProduct): bool
    {
        return auth()->user()->can('restore SPProduct');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, SPProduct $sPProduct): bool
    {
        return auth()->user()->can('force-delete SPProduct');
    }

    public function replicateSPProduct(SPProduct $SPProduct) :bool
    {
        return auth()->user()->can('replicate SPProduct');
    }

    public function reorderSPProduct(SPProduct $SPProduct) :bool
    {
        return auth()->user()->can('reorder SPProduct');
    }
}
