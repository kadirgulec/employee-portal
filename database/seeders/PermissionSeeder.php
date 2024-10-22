<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            //users
            'backend.users.view-any',
            'backend.users.view',
            'backend.users.create',
            'backend.users.update',
            'backend.users.delete',
            'backend.users.restore',
            'backend.users.force-delete',
            'backend.users.replicate',
            'backend.users.reorder',
            'backend.users.permissions',

            //illness-notification
            'backend.illness-notifications.view-any',
            'backend.illness-notifications.view',
            'backend.illness-notifications.create',
            'backend.illness-notifications.update',
            'backend.illness-notifications.delete',
            'backend.illness-notifications.restore',
            'backend.illness-notifications.force-delete',
            'backend.illness-notifications.replicate',
            'backend.illness-notifications.reorder',

            //customer
            'backend.customers.view-any',
            'backend.customers.view',
            'backend.customers.create',
            'backend.customers.update',
            'backend.customers.delete',
            'backend.customers.restore',
            'backend.customers.force-delete',
            'backend.customers.replicate',
            'backend.customers.reorder',

            //bills
            'backend.bills.view-any',
            'backend.bills.view',
            'backend.bills.create',
            'backend.bills.update',
            'backend.bills.delete',
            'backend.bills.restore',
            'backend.bills.force-delete',
            'backend.bills.replicate',
            'backend.bills.reorder',

            //sp-products
            'backend.sp-products.view-any',
            'backend.sp-products.view',
            'backend.sp-products.create',
            'backend.sp-products.update',
            'backend.sp-products.delete',
            'backend.sp-products.restore',
            'backend.sp-products.force-delete',
            'backend.sp-products.replicate',
            'backend.sp-products.reorder',

            //departments
            'backend.departments.view-any',
            'backend.departments.view',
            'backend.departments.create',
            'backend.departments.update',
            'backend.departments.delete',
            'backend.departments.restore',
            'backend.departments.force-delete',
            'backend.departments.replicate',
            'backend.departments.reorder',
        ];

        foreach ($permissions as $permission) {
            Permission::create([
                'name' => $permission,
                'guard_name' => 'web',
            ]);
        }
    }
}
