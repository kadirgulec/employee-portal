<?php

namespace App\Policies;

use App\Models\Bill;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class BillPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return auth()->user()->can('backend.bills.view-any');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Bill $bill): bool
    {
        return auth()->user()->can('backend.bills.view');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return auth()->user()->can('backend.bills.create');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Bill $bill): bool
    {
        return auth()->user()->can('backend.bills.update');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Bill $bill): bool
    {
        return auth()->user()->can('backend.bills.delete');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Bill $bill): bool
    {
        return auth()->user()->can('backend.bills.restore');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Bill $bill): bool
    {
//        return auth()->user()->can('backend.bills.force-delete');
        return false;
    }

    public function replicateBill(Bill $bill) :bool
    {
        return auth()->user()->can('backend.bills.replicate');
    }

    public function reorderBill(Bill $bill) :bool
    {
        return auth()->user()->can('backend.bills.reorder');
    }
}
