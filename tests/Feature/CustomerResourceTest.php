<?php

use App\Filament\Resources\CustomerResource;
use App\Filament\Resources\CustomerResource\Pages\CreateCustomer;
use App\Filament\Resources\CustomerResource\Pages\EditCustomer;
use App\Filament\Resources\CustomerResource\Pages\ListCustomers;
use App\Models\Customer;
use function Pest\Livewire\livewire;

describe('without permissions', function () {
    beforeEach(function () {

        Customer::factory()->count(5)->create();

    });

    it('forbids customer listing', function () {
        $this->get(CustomerResource::getUrl())
            ->assertForbidden();
    });


    it('forbids customer edit', function () {
        $this->get(CustomerResource::getUrl('edit', [
            'record' => Customer::first()->id,
        ]))
            ->assertForbidden();
    });

    it('forbids customer create', function () {
        $this->get(CustomerResource::getUrl('create'))
            ->assertForbidden();
    });
});

describe('->view-any, ->view, ->update , ->create', function () {
    beforeEach(function () {
        auth()->user()->givePermissionTo([
            'backend.customers.view-any',
            'backend.customers.view',
            'backend.customers.update',
            'backend.customers.create'
        ]);
        $this->customers = Customer::factory()->count(5)->create();
    });

    it('can list customers', function () {
        livewire(ListCustomers::class)
            ->assertCanSeeTableRecords($this->customers);
    });

    it('can view customer', function () {
       livewire(ListCustomers::class)
       ->call('mountTableAction','view', $this->customers->first->id)
       ->assertCanSeeTableRecords([$this->customers->first()->name]);
    });

    it('can edit customer', function () {
        livewire(EditCustomer::class, [
            'record' => $this->customers->first()->id,
        ])
        ->call('save')
        ->assertHasNoFormErrors();
    });

    it('can create customer', function () {
        $newCustomer = Customer::factory()->make();
        livewire(CreateCustomer::class)
            ->fillForm([
                'first_name' => $newCustomer->first_name,
                'last_name' => $newCustomer->last_name,
            ])
            ->call('create')
            ->assertHasNoFormErrors();

    });

});

