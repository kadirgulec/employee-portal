<?php

use App\Models\Permission;
use App\Models\User;

it('returns a successful response', function () {
    $user = User::factory()->create();
    Permission::create(['name' => 'view-any User']);
    $user->givePermissionTo('view-any User');
    $this->actingAs($user);
    $response = $this->get('/');
    $response->assertStatus(200);
});


