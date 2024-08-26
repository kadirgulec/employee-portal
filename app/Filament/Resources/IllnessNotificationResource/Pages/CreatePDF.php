<?php

namespace App\Filament\Resources\IllnessNotificationResource\Pages;

use App\Filament\Resources\IllnessNotificationResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreatePDF
{
    protected static string $resource = IllnessNotificationResource::class;

    public static function PDF()
    {
        echo 'Hello';
    }
}
