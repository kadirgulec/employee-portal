<?php

use App\Filament\Resources\UserResource;
use App\Models\User;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use function Pest\Livewire\livewire;


it('can render UserList', function () {
    $this->get(UserResource::getUrl('index'))->assertSuccessful();
});

it('can list users', function () {
    $users = User::factory()->count(5)->create();

    livewire(UserResource\Pages\ListUsers::class)
        ->assertCanSeeTableRecords($users);
});

it('cannot display trashed users by default', function () {
    $users = User::factory()->count(4)->create();
    $trashedUsers = User::factory()->trashed()->count(6)->create();

    livewire(UserResource\Pages\ListUsers::class)
        ->assertCanSeeTableRecords($users)
        ->assertCanNotSeeTableRecords($trashedUsers)
        ->assertCountTableRecords(5); //because of authenticated user the count must be +1 here
});
it('can search users by full name', function () {
    $users = User::factory()->count(5)->create();

    $full_name = $users->first()->full_name;

    livewire(UserResource\Pages\ListUsers::class)
        ->searchTable($full_name)
        ->assertCanSeeTableRecords($users->where('full_name', $full_name))
        ->assertCanNotSeeTableRecords($users->where('full_name', '!=', $full_name));
});

it('can create user', function () {
    $newUser = User::factory()->make();

    livewire(UserResource\Pages\CreateUser::class)
        ->fillForm([
            'name' => $newUser->name,
            'email' => $newUser->email,
            'password' => 'password',
            'first_name' => $newUser->first_name,
            'last_name' => $newUser->last_name,
            'pin' => $newUser->pin,
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    $this->assertDatabaseHas(User::class, [
        'name' => $newUser->name,
        'email' => $newUser->email,
        'first_name' => $newUser->first_name,
        'last_name' => $newUser->last_name,
        'pin' => $newUser->pin,
    ]);
    $createdUser = User::where('email', $newUser->email)->first();
    expect(Hash::check('password', $createdUser->password))->toBeTrue();
});

it('can validate required fields', function () {
    livewire(UserResource\Pages\CreateUser::class)
        ->fillForm([
            'name' => null,
            'email' => null,
            'first_name' => null,
            'last_name' => null,
            'pin' => null,
        ])
        ->call('create')
        ->assertHasFormErrors([
            'name' => 'required',
            'email' => 'required',
            'first_name' => 'required',
            'last_name' => 'required',
            'pin' => 'required',
        ]);
});
it('can validate email format and numeric pin', function () {

    livewire(UserResource\Pages\CreateUser::class)
        ->fillForm([
            'email' => 'aaaa',
            'pin' => 'null',
        ])
        ->call('create')
        ->assertHasFormErrors([
            'email' => 'email',
            'pin' => 'numeric',
        ]);
});

it('can validate unique email', function () {
    $user = User::first();

    livewire(UserResource\Pages\CreateUser::class)
        ->fillForm([
            'email' => $user->email,
        ])
        ->call('create')
        ->assertHasFormErrors([
            'email' => 'unique',
        ]);

});

it('has edit form', function () {
    $user = User::factory()->create();
    livewire(UserResource\Pages\EditUser::class, [
        'record' => $user->getRouteKey(),
    ])
        ->assertFormExists();
});

it('can retrieve data', function () {
    $user = User::factory()->create();

    livewire(UserResource\Pages\EditUser::class, [
        'record' => $user->getRouteKey(),
    ])
        ->assertFormSet([
            'personal_number' => $user->personal_number,
            'name' => $user->name,
            'email' => $user->email,
            'first_name' => $user->first_name,
            'last_name' => $user->last_name,
            'pin' => $user->pin,
            'gender' => $user->gender,
            'title' => $user->title,
            'position' => $user->position,
            'phone' => $user->phone,
            'mobile' => $user->mobile,
        ]);
});

it('can save', function () {
    $user = User::factory()->create();
    $newUser = User::factory()->make();

    livewire(UserResource\Pages\EditUser::class, [
        'record' => $user->getRouteKey(),
    ])
        ->fillForm([
            'personal_number' => $newUser->personal_number,
            'name' => $newUser->name,
            'email' => $newUser->email,
            'first_name' => $newUser->first_name,
            'last_name' => $newUser->last_name,
            'pin' => $newUser->pin,
            'gender' => $newUser->gender,
            'title' => $newUser->title,
            'position' => $newUser->position,
            'phone' => $newUser->phone,
            'mobile' => $newUser->mobile,
        ])
        ->call('save')
        ->assertHasNoFormErrors();

    expect($user->refresh())
        ->personal_number->toBe($newUser->personal_number)
        ->name->toBe($newUser->name)
        ->email->toBe($newUser->email)
        ->first_name->toBe($newUser->first_name)
        ->last_name->toBe($newUser->last_name)
        ->pin->toBe($newUser->pin)
        ->gender->toBe($newUser->gender)
        ->title->toBe($newUser->title)
        ->position->toBe($newUser->position)
        ->phone->toBe($newUser->phone)
        ->mobile->toBe($newUser->mobile);
});

it('can soft delete', function () {

    $newUser = User::factory()->make();
    $user = User::factory()->create($newUser->getAttributes());

    livewire(UserResource\Pages\EditUser::class, [
        'record' => $user->getRouteKey(),
    ])
        ->callAction(DeleteAction::class);

    $this->assertSoftDeleted('users', $newUser->getAttributes());
});

it('cannot delete if permission is revoked', function (){
    $user = User::factory()->create();
    auth()->user()->revokePermissionTo('backend.users.delete');

    livewire(UserResource\Pages\EditUser::class, [
        'record' => $user->getRouteKey(),
    ])
        ->assertActionHidden(DeleteAction::class);
});

it('cannot delete himself', function (){
    livewire(UserResource\Pages\EditUser::class, [
        'record' => auth()->user()->getRouteKey(),
    ])
        ->assertActionHidden(DeleteAction::class);
});
