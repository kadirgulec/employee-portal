<?php

namespace App\Filament\Resources\WorkInstructionResource\Pages;

use App\Filament\Resources\WorkInstructionResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListWorkInstructions extends ListRecords
{
    protected static string $resource = WorkInstructionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
