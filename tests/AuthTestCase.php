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
        $user->givePermissionTo([
            'backend.users.view-any',
            'backend.users.view',
            'backend.users.create',
            'backend.users.update',
            'backend.users.delete',
        ]);
        $this->actingAs($user);
    }
}
