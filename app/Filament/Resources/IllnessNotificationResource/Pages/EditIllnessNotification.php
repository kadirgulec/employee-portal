<?php

namespace App\Filament\Resources\IllnessNotificationResource\Pages;

use App\Filament\Resources\IllnessNotificationResource;
use Filament\Actions;
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
}
