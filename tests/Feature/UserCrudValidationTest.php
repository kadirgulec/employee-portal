<?php

use App\Filament\Resources\UserResource;
use App\Filament\Resources\UserResource\Pages\ListUsers;
use App\Filament\Resources\UserResource\Pages\ViewUser;
use App\Livewire\LanguageSwitcher;
use App\Models\Department;
use App\Models\IllnessNotification;
use App\Models\User;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use function Pest\Livewire\livewire;


it('can render UserList', function () {
    $this->get(UserResource::getUrl('index'))
        ->assertSuccessful();
});
it('can render avatar', function () {
    auth()->user()->update(['avatar' => '/01J5Z3PDSYENPDJS3R93MGWHNR.jpg']);

    livewire(\Filament\Pages\Dashboard::class)
        ->assertSuccessful(); //TODO
});

it('can change language', function () {
    expect(App::getLocale())->toBe('en');

    livewire(LanguageSwitcher::class)
        ->call('switchLanguage', 'de');

    expect(App::getLocale())->toBe('de');

    livewire(LanguageSwitcher::class)
        ->call('switchLanguage', 'de');

    expect(App::getLocale())->toBe('de');
});

it('can render permissions', function () {
    auth()->user()->givePermissionTo('backend.users.permissions');
    $this->get(UserResource::getUrl('permissions', ['record' => auth()->user()->id]))->assertSuccessful();
});

it('can give or revoke permission', function () {
    auth()->user()->givePermissionTo('backend.users.permissions');
    $user1 = User::factory()->create();

    $viewAnyPermission = \App\Models\Permission::first();
    $viewAnyCreatedAt = $viewAnyPermission->created_at->toISOString();
    $viewAnyUpdatedAt = $viewAnyPermission->updated_at->toISOString();
    $viewAnyDataLink = 'data.{"id":'.$viewAnyPermission->id.',"name":"'.$viewAnyPermission->name.'","guard_name":"'.$viewAnyPermission->guard_name.'","created_at":"'.$viewAnyCreatedAt.'","updated_at":"'.$viewAnyUpdatedAt.'"}';


    expect($user1->hasPermissionTo($viewAnyPermission->name))->toBeFalse();

    livewire(UserResource\Pages\PermissionsUser::class, [
        'record' => $user1->getRouteKey()
    ])
        ->set($viewAnyDataLink, true);


    expect($user1->fresh()->hasPermissionTo($viewAnyPermission->name))->toBeTrue();

    livewire(UserResource\Pages\PermissionsUser::class, [
        'record' => $user1->getRouteKey()
    ])
        ->set($viewAnyDataLink, false);

    expect($user1->hasPermissionTo($viewAnyPermission->name))->toBeFalse();

});

it('can render department tabs', function () {


    $department1 = Department::factory()->create(['name' => 'Department1']);
    $department2 = Department::factory()->create(['name' => 'Department2']);


    $user1 = User::factory()->create();
    $user2 = User::factory()->create();

    $user1->department_user()->create([
        'department_id' => $department1->id,
        'user_id' => $user1->id,
        'leader' => false,
    ]);

    $user2->department_user()->create([
        'department_id' => $department2->id,
        'user_id' => $user2->id,
        'leader' => false,
    ]);


    livewire(ListUsers::class)
        ->assertSuccessful();
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

it('can view user', function () {
    $user1 = User::factory()->create();

    livewire(ViewUser::class, [
        'record' => $user1->getRouteKey(),
    ])
        ->assertSuccessful();
});


describe('can view user with departments', function () {
    beforeEach(function () {
        $this->department1 = Department::factory()->create(['name' => 'Department1']);
        $this->department2 = Department::factory()->create(['name' => 'Department2']);


        $this->user1 = User::factory()->create();
        $this->user2 = User::factory()->create();

        $this->user1->department_user()->create([
            'department_id' => $this->department1->id,
            'user_id' => $this->user1->id,
            'leader' => false,
        ]);


        $this->user2->department_user()->create([
            'department_id' => $this->department2->id,
            'user_id' => $this->user2->id,
            'leader' => false,
        ]);
    });

    it('see departments', function () {
        livewire(UserResource\RelationManagers\DepartmentsRelationManager::class, [
            'ownerRecord' => $this->user1,
            'pageClass' => UserResource\Pages\viewUser::class,
        ])
            ->assertSee(str($this->department1->name)->slug()->toString())
            ->assertDontSee(str($this->department2->name)->slug()->toString());
    });

    it('has single title', function () {
        livewire(UserResource\RelationManagers\DepartmentsRelationManager::class, [
            'ownerRecord' => $this->user1,
            'pageClass' => UserResource\Pages\viewUser::class,
        ])
            ->assertSee(__('filament-panels::translations.department.single'));
    });

    it('has plural title', function () {
        $this->user1->department_user()->create([
            'department_id' => $this->department2->id,
            'user_id' => $this->user1->id,
            'leader' => false,
        ]);

        livewire(UserResource\RelationManagers\DepartmentsRelationManager::class, [
            'ownerRecord' => $this->user1,
            'pageClass' => UserResource\Pages\viewUser::class,
        ])
            ->assertSee(__('filament-panels::translations.department.plural'));
    });
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
//            'phone' => $newUser->phone,
//            'mobile' => $newUser->mobile,
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
        ->position->toBe($newUser->position);
//        ->phone->toBe($newUser->phone)
//        ->mobile->toBe($newUser->mobile);
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

it('cannot delete if permission is revoked', function () {
    $user = User::factory()->create();
    auth()->user()->revokePermissionTo('backend.users.delete');

    livewire(UserResource\Pages\EditUser::class, [
        'record' => $user->getRouteKey(),
    ])
        ->assertActionHidden(DeleteAction::class);
});

it('cannot delete himself', function () {
    livewire(UserResource\Pages\EditUser::class, [
        'record' => auth()->user()->getRouteKey(),
    ])
        ->assertActionHidden(DeleteAction::class);
});
