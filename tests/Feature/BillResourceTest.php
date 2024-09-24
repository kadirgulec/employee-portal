<?php

use App\Filament\Resources\BillResource\Pages\EditBill;
use App\Filament\Resources\BillResource\Pages\ListBills;
use App\Filament\Resources\BillResource\Pages\ViewBill;
use App\Models\Bill;
use App\Models\Customer;
use function Pest\Livewire\livewire;

describe('->view-any, ->view, -> update , ->create', function () {
    beforeEach(function () {
        auth()->user()->givePermissionTo([
            'backend.bills.view-any',
            'backend.bills.view',
            'backend.bills.update',
            'backend.bills.create'
        ]);

        $this->customer = Customer::factory()->create();
        $this->bill = Bill::factory([
            'customer_id' => $this->customer->id,
            'created_by' => auth()->user()->id,
            'date' => now()
        ])->create();
    });

    it('can list bills', function () {
        livewire(ListBills::class)
            ->assertSee($this->customer->full_name);
    });

    it('can view bill', function () {
        livewire(viewBill::class, [
            'record' => $this->bill->id,
        ])
            ->assertSee($this->customer->full_name);
    });

    it('can update bill', function () {

        livewire(EditBill::class, [
            'record' => $this->bill->id,
        ])
            ->fillForm([
                'date' => now(),
            ])
            ->call('save')
            ->assertHasNoFormErrors();
    });

    it('can download Bill PDF', function () {

        $this->get('bill/'. $this->bill->id.'/pdf')
            ->assertDownload();
    });

});
