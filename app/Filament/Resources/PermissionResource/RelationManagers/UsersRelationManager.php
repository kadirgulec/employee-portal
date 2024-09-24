<?php

namespace App\Filament\Resources\PermissionResource\RelationManagers;

use App\Filament\Resources\UserResource\Pages\PermissionsUser;
use App\Models\User;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;


class UsersRelationManager extends RelationManager
{
    protected static string $relationship = 'users';

//    public static function getRecordTitleAttribute(): string
//    {
//        return 'name';
//    }

//    public function form(Form $form): Form
//    {
//        return PermissionsUser::getPermissionsSchema($form);
//    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitle(fn(User $user): string => optional($user)->full_name)
            ->columns([
                Tables\Columns\TextColumn::make('full_name'),
                Tables\Columns\ImageColumn::make('avatar')
                    ->circular(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\AttachAction::make()
                    ->preloadRecordSelect()
                    ->recordSelectSearchColumns(['first_name', 'last_name']),
            ])
            ->actions([
//                Tables\Actions\EditAction::make()
//                    ->ModalFooterActions([
//                        Action::make('cancel')
//                            ->label(__('filament-panels::resources/pages/edit-record.form.actions.cancel.label'))
//                            ->getModalCancelAction()
//                            ->color('gray'),
//                    ]),
                Tables\Actions\DetachAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DetachBulkAction::make(),
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

//    public function getFormActions(): array
//    {
//        return [
//
//        ];
//    }
}
