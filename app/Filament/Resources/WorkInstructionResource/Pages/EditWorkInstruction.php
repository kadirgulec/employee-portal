<?php

namespace App\Filament\Resources\WorkInstructionResource\Pages;

use App\Filament\Resources\WorkInstructionResource;
use Filament\Actions;
use Filament\Resources\Pages\ContentTabPosition;
use Filament\Resources\Pages\EditRecord;

class EditWorkInstruction extends EditRecord
{
    protected static string $resource = WorkInstructionResource::class;


    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
