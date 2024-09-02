<?php

namespace Tests;

use App\Models\Permission;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use RefreshDatabase;
    protected function setUp(): void
    {
        parent::setUp();
//        $user = User::factory()->create();
//        Permission::create(['name' => 'view-any User']);
//        $user->givePermissionTo('view-any User');
//        $this->actingAs($user);
    }
}
