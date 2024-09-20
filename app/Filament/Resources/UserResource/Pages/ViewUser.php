<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewUser extends ViewRecord
{
    protected static string $resource = UserResource::class;


    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
            Actions\Action::make('permissions')
                ->label(__('filament-panels::translations.user.permissions'))
                ->url($this->record->id. '/permissions')
                ->visible(auth()->user()->can('backend.users.permissions'))
            ->icon('heroicon-o-key')
            ->outlined(),
        ];
    }
}
