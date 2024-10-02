<?php

namespace App\Filament\Resources\BillResource\Pages;

use App\Filament\Resources\BillResource;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Resources\Pages\EditRecord;

class EditBill extends EditRecord
{
    protected static string $resource = BillResource::class;
    protected ?bool $hasUnsavedDataChangesAlert = false;

function getHeaderActions(): array
{
    return [
        Actions\ViewAction::make(),
        Actions\DeleteAction::make(),
        Actions\ForceDeleteAction::make(),
        Actions\RestoreAction::make(),

    ];
}

protected
function getFormActions(): array
{
    return [
        $this->getSaveFormAction(),
        Action::make('PDF')
            ->label('PDF')
            ->action(function ($record) {
                $this->save();
                redirect()->route('bill.pdf', $record);
            })
            ->color('info')
            ->openUrlInNewTab(),
        $this->getCancelFormAction(),
    ];
}
}
