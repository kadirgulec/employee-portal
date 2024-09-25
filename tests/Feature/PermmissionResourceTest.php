<?php

use App\Filament\Resources\PermissionResource\Pages\EditPermission;
use App\Filament\Resources\PermissionResource\Pages\ListPermissions;
use App\Filament\Resources\PermissionResource\RelationManagers\UsersRelationManager;
use App\Filament\Resources\UserResource;
use App\Models\Permission;
use App\Models\User;
use function Pest\Livewire\livewire;

describe('PermissionResource', function () {
   beforeEach(function () {
       auth()->user()->givePermissionTo([
           'backend.users.permissions'
       ]);
   }) ;

   it('can list permissions', function () {
      livewire(ListPermissions::class)
          ->assertCanSeeTableRecords(
              Permission::all()->take(7) //because of pagination I get just the first 7 permissions
          );
   });

   //edit permission page is to attach and detach users to any permission easily
   it('can render edit permission', function () {
       livewire(EditPermission::class,[
           'record' => Permission::first()->id,
       ])
           ->assertOk();
   });

   it('can see authorized users for any permission', function () {
       $user = User::factory()->create();

       $user->givePermissionTo([Permission::first()->name]);
       livewire(UsersRelationManager::class,[
           'ownerRecord' => Permission::first(),
           'pageClass' => UserResource::class,
       ])
       ->assertSee([
           $user->first_name,
       ]);
   });
});
