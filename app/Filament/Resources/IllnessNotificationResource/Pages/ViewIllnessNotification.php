<?php

namespace App\Filament\Resources\IllnessNotificationResource\Pages;

use App\Filament\Resources\IllnessNotificationResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewIllnessNotification extends ViewRecord
{
    protected static string $resource = IllnessNotificationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
