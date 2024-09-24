<?php

use App\Filament\Resources\SPProductResource;
use App\Filament\Resources\SPProductResource\Pages\EditSPProduct;
use App\Filament\Resources\SPProductResource\Pages\ListSPProducts;
use App\Filament\Resources\SPProductResource\Pages\ViewSPProduct;
use App\Models\SPProduct;
use function Pest\Livewire\livewire;

describe('without permissions', function () {
    beforeEach(function () {

        SPProduct::factory()->count(5)->create();

    });

    it('forbids sp-products listing', function () {
        $this->get(SPProductResource::getUrl())
            ->assertForbidden();
    });


    it('forbids sp-product edit', function () {
        $this->get(SPProductResource::getUrl('edit', [
            'record' => SPProduct::first()->id,
        ]))
            ->assertForbidden();
    });

    it('forbids sp-product create', function () {
        $this->get(SPProductResource::getUrl('create'))
            ->assertForbidden();
    });
});

describe('->view-any, ->view, ->update, ->create', function () {
    beforeEach(function () {
        auth()->user()->givePermissionTo([
            'backend.sp-products.view-any',
            'backend.sp-products.view',
            'backend.sp-products.update',
            'backend.sp-products.create'
        ]);
        $this->products = SPProduct::factory()->count(5)->create();
    });

    it('can list products', function () {
        livewire(ListSPProducts::class)
            ->assertCanSeeTableRecords($this->products);
    });

    it('can view a product', function () {
        $product = SPProduct::first();

        livewire(ViewSPProduct::class, [
            'record' => $product->id,
        ])
            ->assertSet('data.name', $product->name);
    });

    it('can edit a product', function () {
        $newProduct = SPProduct::factory()->make();

        $product = $this->products->first();

        livewire(EditSPProduct::class, [
            'record' => $product->id,
        ])
            ->fillForm([
                'name' => $newProduct->name,
                'description' => $newProduct->description,
                'price' => $newProduct->price,
            ])
            ->call('save')
            ->assertHasNoFormErrors();

        $this->assertDatabaseHas('sp_products', [
            'name' => $newProduct->name,
            'description' => $newProduct->description,
            'price' => $newProduct->price,
        ]);
    });

});
