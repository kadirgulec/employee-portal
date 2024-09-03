<?php

namespace App\Filament\Resources\SPProductResource\Pages;

use App\Filament\Resources\SPProductResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewSPProduct extends ViewRecord
{
    protected static string $resource = SPProductResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
