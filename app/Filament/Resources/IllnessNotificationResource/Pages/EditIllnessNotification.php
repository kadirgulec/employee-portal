<?php

namespace App\Filament\Resources\IllnessNotificationResource\Pages;

use App\Filament\Resources\IllnessNotificationResource;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Resources\Pages\EditRecord;

class EditIllnessNotification extends EditRecord
{
    protected static string $resource = IllnessNotificationResource::class;



    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
            Actions\ForceDeleteAction::make(),
            Actions\RestoreAction::make(),

        ];
    }

    protected function getFormActions(): array
    {
        return [
            $this->getSaveFormAction(),
            Action::make('SaveAndPDF')
                ->label(__('filament-panels::translations.illness_notifications.create_PDF'))
                ->url(fn($record) => route("illness-notifications.pdf", $record))
                ->color('info')
                ->openUrlInNewTab(),
            $this->getCancelFormAction(),
        ];
    }
}
