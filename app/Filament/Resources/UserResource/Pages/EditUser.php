<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditUser extends EditRecord
{
    protected static string $resource = UserResource::class;

    public function getTitle(): string
    {
        $gender = $this->record->gender;
        return $gender === 'female'
            ? __('filament-panels::translations.user.edit.female')
            : __('filament-panels::translations.user.edit.male');
    }


    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('permissions')
            ->url( 'permissions' )
            ->visible(auth()->user()->can('backend.users.permissions')),
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
            Actions\ForceDeleteAction::make(),
            Actions\RestoreAction::make(),
        ];
    }
}
