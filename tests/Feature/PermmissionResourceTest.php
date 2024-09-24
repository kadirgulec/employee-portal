<?php

use App\Filament\Resources\PermissionResource\Pages\ListPermissions;
use App\Filament\Resources\UserResource;
use App\Models\Permission;
use function Pest\Livewire\livewire;

describe('PermmissionResource', function () {
   beforeEach(function () {
       auth()->user()->givePermissionTo([
           'backend.users.permissions'
       ]);
   }) ;

   it('can list permissions', function () {
      livewire(ListPermissions::class)
          ->assertCanSeeTableRecords(
              Permission::all()->take(7) //because of pagination i get just the first 7 permissions
          );
   });

   //edit permission page is to attach and detach users to any permission easily
   it('can render edit permission', function () {
       livewire(\App\Filament\Resources\PermissionResource\Pages\EditPermission::class,[
           'record' => Permission::first()->id,
       ])
           ->assertOk();
   });

   it('can see authorized users for any permission', function () {
       $user = \App\Models\User::factory()->create();

       $user->givePermissionTo([Permission::first()->name]);
       livewire(\App\Filament\Resources\PermissionResource\RelationManagers\UsersRelationManager::class,[
           'ownerRecord' => Permission::first(),
           'pageClass' => UserResource::class,
       ])
       ->assertSee([
           $user->first_name,
       ]);
   });
});
