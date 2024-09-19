<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditUser extends EditRecord
{
    protected static string $resource = UserResource::class;

    public function getBreadcrumbs(): array
    {
        $resource = static::getResource();
        return
            [
                    static::$breadcrumb ?? $resource::getUrl('index') => __('filament-panels::translations.user.plural'),
                    static::$breadcrumb ?? $resource::getUrl('view', [$this->record]) => $this->record->full_name,
                    static::$breadcrumb ?? __('filament-panels::resources/pages/edit-record.breadcrumb'),
            ];
    }

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
                ->label(__('filament-panels::translations.user.permissions'))
                ->url('permissions')
                ->outlined()
                ->icon('heroicon-o-lock-open')
                ->visible(auth()->user()->can('backend.users.permissions')),
//            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
            Actions\ForceDeleteAction::make(),
            Actions\RestoreAction::make(),
        ];
    }
}
