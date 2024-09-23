<?php

use App\Filament\Resources\IllnessNotificationResource\Pages\CreateIllnessNotification;
use App\Filament\Resources\IllnessNotificationResource\Pages\EditIllnessNotification;
use App\Filament\Resources\IllnessNotificationResource\Pages\ListIllnessNotifications;
use App\Filament\Resources\UserResource\Pages\CreateUser;
use App\Filament\Resources\UserResource\Pages\EditUser;
use App\Models\IllnessNotification;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Permission;
use function Spatie\RouteTesting\routeTesting;
use function Pest\Livewire\livewire;


routeTesting('it render the login page')
    ->include(['login'])
    ->assertSuccessful();

routeTesting('it redirects unlogged users')
    ->exclude(['login', 'up', 'livewire/*', 'filament/*'])
    ->bind('record', (fn() => User::factory()->create()))
    ->ignoreRoutesWithMissingBindings()
    ->assertRedirect('/login');

describe('logged user', function () {
    beforeEach(function () {
        $this->actingAs(User::factory()->create());
    });
    routeTesting('it render dashboard')
        ->include(['/', 'users'])
        ->assertSuccessful();

    routeTesting('it forbids unauthorised users')
        ->exclude(['login', 'up', 'livewire/*', 'filament/*', '/', 'users'])
        ->ignoreRoutesWithMissingBindings()
        ->assertForbidden();
});

describe('view-any User authenticated user', function () {
    beforeEach(function () {
        $user = User::factory()->create();
        Permission::create(['name' => 'backend.users.view-any']);
        $user->givePermissionTo('backend.users.view-any');
        $this->actingAs($user);
    });
    routeTesting('it render authenticated pages')
        ->include(['users'])
        ->assertSuccessful();

    it('allows a user to edit their own page', function () {
        livewire(EditUser::class, ['record' => Auth::user()->id])
            ->assertSuccessful();
    });

    routeTesting('it forbids unauthorised pages')
        ->exclude(['/', 'login', 'up', 'users', 'livewire/*', 'illness-notifications/*/edit', 'departments/*/edit'])
        ->ignoreRoutesWithMissingBindings()
        ->assertForbidden();
});

describe('edit User and create user authenticated users', function () {
    beforeEach(function () {
        $user = User::factory()->create();
        Permission::create(['name' => 'backend.users.view-any']);
        Permission::create(['name' => 'backend.users.update']);
        Permission::create(['name' => 'backend.users.create']);
        $user->givePermissionTo(['backend.users.view-any', 'backend.users.update', 'backend.users.create']);
        $this->actingAs($user);
    });
    it('allows authenticated user to render edit page of any User', function () {
        livewire(EditUser::class, ['record' => User::factory()->create()->id])
            ->assertSuccessful();
    });

    it('allows authenticated user to render create new User Page', function () {
        livewire(CreateUser::class)
            ->assertSuccessful();
    });
});

describe('IllnessNotification contact', function () {
    beforeEach(function () {
        $user = User::factory()->create();
        Permission::create(['name' => 'backend.illness-notifications.view-any']);
        Permission::create(['name' => 'backend.illness-notifications.update']);
        Permission::create(['name' => 'backend.illness-notifications.create']);
        $user->givePermissionTo([
            'backend.illness-notifications.view-any', 'backend.illness-notifications.update', 'backend.illness-notifications.create'
        ]);
        $this->actingAs($user);
    });

    it('allows authenticated user to render edit page of any IllnessNotification', function () {
        livewire(EditIllnessNotification::class, [
            'record' => (function () {
                User::factory()->has(IllnessNotification::factory(), 'illness_notifications')->create()->id;
                return IllnessNotification::find(1)->id;
            })()
        ])->assertSuccessful();

    });

    it('allows authenticated user to render create page of IllnessNotification', function () {
        livewire(CreateIllnessNotification::class)
            ->assertSuccessful();

    });

    it('can render illness notification list', function () {
        livewire(ListIllnessNotifications::class)
        ->assertSuccessful();
    });
});
