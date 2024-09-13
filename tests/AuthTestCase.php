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
        Permission::create(['name' => 'view-any User']);
        Permission::create(['name' => 'view User']);
        Permission::create(['name' => 'create User']);
        Permission::create(['name' => 'update User']);
        Permission::create(['name' => 'delete User']);
        $user->givePermissionTo([
            'view-any User',
            'view User',
            'create User',
            'update User',
            'delete User',
        ]);
        $this->actingAs($user);
    }
}
