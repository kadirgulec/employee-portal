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
     * User can update the model, if he has the permission and
     * if the status of model is 'new',
     * or it is updated(pdf generated) in less than 10 minutes,
     * and it is created less than 2 hours ago
     */
    public function update(User $user, Bill $bill): bool
    {
        return auth()->user()->can('backend.bills.update') &&
            $bill->status != 'completed' &&
            ($bill->status == 'new' ||
                ($bill->updated_at > now()->subMinutes(10) &&
                    $bill->created_at > now()->subHours(2)));
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

    public function replicateBill(Bill $bill): bool
    {
        return auth()->user()->can('backend.bills.replicate');
    }

    public function reorderBill(Bill $bill): bool
    {
        return auth()->user()->can('backend.bills.reorder');
    }
}
