<?php

namespace App\Filament\Resources\SPProductResource\Pages;

use App\Filament\Resources\SPProductResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSPProduct extends EditRecord
{
    protected static string $resource = SPProductResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
            Actions\ForceDeleteAction::make(),
            Actions\RestoreAction::make(),
        ];
    }
}
