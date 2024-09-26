<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Resources\Pages\EditRecord;
use Filament\Resources\RelationManagers\RelationGroup;
use Filament\Resources\RelationManagers\RelationManagerConfiguration;

class EditUser extends EditRecord
{
    protected static string $resource = UserResource::class;

    /**
     * customizes the breadcrumbs
     *
     * @return array|string[]
     */
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

    /**
     * to obtain a gender-specific header
     *
     * @return string
     */
    public function getTitle(): string
    {
        $gender = $this->record->gender;
        return $gender === 'female'
            ? __('filament-panels::translations.user.edit.female')
            : __('filament-panels::translations.user.edit.male');
    }


    // The relation manager is not needed for Edit User, so it returns an empty array,
    // otherwise it will automatically get the department relation
    public function getRelationManagers(): array
    {
        return [];
    }

    /**
     * @return array|Action[]|ActionGroup[]
     */
    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\Action::make('permissions')
                ->label(__('filament-panels::translations.user.permissions'))
                ->url('permissions')
                ->outlined()
                ->icon('heroicon-o-key')
                ->visible(auth()->user()->can('backend.users.permissions')),

            ActionGroup::make([
                Actions\DeleteAction::make(),
                Actions\ForceDeleteAction::make(),
                Actions\RestoreAction::make(),
            ]),

        ];
    }
}
