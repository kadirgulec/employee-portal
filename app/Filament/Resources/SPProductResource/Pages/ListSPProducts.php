<?php

namespace App\Filament\Resources\SPProductResource\Pages;

use App\Filament\Resources\SPProductResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSPProducts extends ListRecords
{
    protected static string $resource = SPProductResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
