<?php

use App\Filament\Resources\DepartmentResource;
use App\Filament\Resources\DepartmentResource\RelationManagers\DepartmentUsersRelationManager;
use App\Filament\Resources\UserResource\RelationManagers\DepartmentsRelationManager;
use App\Models\Department;
use App\Models\User;
use function Pest\Livewire\livewire;

describe('without permissions', function () {
    beforeEach(function () {
        auth()->user()->revokePermissionTo(['backend.departments.view-any', 'backend.departments.view']);
        Department::factory()->count(5)->create();

    });

    it('forbids departments listing', function () {
        $this->get(DepartmentResource::getUrl('index'))
            ->assertForbidden();
    });

    it('forbids department edit', function () {
        $this->get(DepartmentResource::getUrl('edit', [
            'record' => Department::first()->id,
        ]))
            ->assertForbidden();
    });

    it('forbisds deparmtent creation', function () {
        $this->get(DepartmentResource::getUrl('create'))
            ->assertForbidden();
    });
});

describe('->view-any, ->create, ->update', function () {
    beforeEach(function () {
        auth()->user()->givePermissionTo([
            'backend.departments.view-any', 'backend.departments.create', 'backend.departments.update'
        ]);
        $this->departments = Department::factory()->count(5)->create();

        foreach ($this->departments as $department) {
            for ($i = 0; $i <= 3; $i++) {
                $user = User::factory()->create();

                $user->department_user()->create([
                    'department_id' => $department->id,
                    'user_id' => $user->id,
                    'leader' => false,
                ]);
            }
        }

        $user = User::factory()->create();
        $user->department_user()->create([
            'department_id' => $this->departments[0]->id,
            'user_id' => $user->id,
            'leader' => true,
        ]);
    });

    it('can view Dashboard', function () {
        $this->get('/')->assertOK();
    });

    it('can view departments listing', function () {
        livewire(DepartmentResource\Pages\ListDepartments::class)
            ->assertCanSeeTableRecords($this->departments);
    });

    it('can edit department', function () {
        livewire(DepartmentResource\Pages\EditDepartment::class, [
            'record' => Department::first()->id,
        ])
            ->assertSuccessful();
    });

    it('can render Employees at department', function () {
        livewire(DepartmentUsersRelationManager::class, [
            'ownerRecord' => Department::first(),
            'pageClass' => DepartmentResource\Pages\EditDepartment::class,
        ])
            ->assertOk();
    });
});
