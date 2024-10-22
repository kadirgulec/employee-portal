<?php

namespace App\Filament\Resources\WorkInstructionResource\Pages;

use App\Filament\Resources\WorkInstructionResource;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Resources\Pages\ViewRecord;
use Illuminate\Database\Eloquent\Model;

class ViewWorkInstruction extends ViewRecord
{
    protected static string $resource = WorkInstructionResource::class;



    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }


}
