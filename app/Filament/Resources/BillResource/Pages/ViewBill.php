<?php

namespace App\Filament\Resources\BillResource\Pages;

use App\Filament\Resources\BillResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewBill extends ViewRecord
{
    protected static string $resource = BillResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
            Actions\Action::make('PDF')
            ->label('PDF')
            ->color('info')
            ->icon('heroicon-o-document-arrow-down')
            ->url(fn($record): ?string => route('bill.pdf', $record))
            ->openUrlInNewTab()
        ];
    }
}
