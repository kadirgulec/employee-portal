<?php

namespace Tests;

use App\Models\Permission;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class AuthTestCase extends TestCase
{
    use RefreshDatabase;


    protected function setUp(): void
    {
        parent::setUp();
        $user = User::factory()->create();
        Permission::create(['name' => 'backend.users.view-any']);
        Permission::create(['name' => 'backend.users.view']);
        Permission::create(['name' => 'backend.users.create']);
        Permission::create(['name' => 'backend.users.update']);
        Permission::create(['name' => 'backend.users.delete']);
        Permission::create(['name' => 'backend.users.permissions']);
        Permission::create(['name' => 'backend.illness-notifications.view-any']);
        Permission::create(['name' => 'backend.illness-notifications.view']);
        Permission::create(['name' => 'backend.departments.view-any']);
        Permission::create(['name' => 'backend.departments.view']);
        Permission::create(['name' => 'backend.departments.create']);
        Permission::create(['name' => 'backend.departments.update']);
        Permission::create(['name' => 'backend.customers.view-any']);
        Permission::create(['name' => 'backend.customers.view']);
        Permission::create(['name' => 'backend.customers.create']);
        Permission::create(['name' => 'backend.customers.update']);
        $user->givePermissionTo([
            'backend.users.view-any',
            'backend.users.view',
            'backend.users.create',
            'backend.users.update',
            'backend.users.delete',
            'backend.illness-notifications.view-any',
            'backend.illness-notifications.view',
            'backend.departments.view-any',
            'backend.departments.view',
        ]);
        $this->actingAs($user);
    }
}
