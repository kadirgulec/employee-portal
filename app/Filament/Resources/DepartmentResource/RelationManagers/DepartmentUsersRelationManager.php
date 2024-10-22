<?php

namespace App\Filament\Resources\DepartmentResource\RelationManagers;

use App\Models\User;
use Filament\Forms\Components\Toggle;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class DepartmentUsersRelationManager extends RelationManager
{
    protected static string $relationship = 'department_users';


    /**
     * @return string
     */
    public static function getTitle(Model $ownerRecord, string $pageClass): string
    {
        return __('filament-panels::translations.department.users');

    }


    public function table(Table $table): Table
    {
        return $table
            ->recordTitle(fn(User $user): string => optional($user)->full_name)
            ->columns([
                Tables\Columns\TextColumn::make('full_name')
                    ->label(__('filament-panels::translations.user.name')),
                Tables\Columns\ImageColumn::make('avatar')
                    ->circular(),
                Tables\Columns\IconColumn::make('leader')
                    ->label(__('filament-panels::translations.user.leader'))
                    ->icon(fn(string $state): string => match ($state) {
                        '1' => 'heroicon-o-trophy',
                        '0' => 'heroicon-o-user-circle',
                    })
                    ->color(fn(string $state): string => match ($state) {
                        '1' => 'success',
                        '0' => 'danger',
                    })
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\AttachAction::make()
                    ->modalHeading(__('filament-panels::translations.department.add_user'))
                    ->preloadRecordSelect()
                    ->recordSelectSearchColumns(['first_name', 'last_name'])
                    ->color('primary')
                    ->form(fn(Tables\Actions\AttachAction $action): array => [
                        $action->getRecordSelect(),
                        Toggle::make('leader')
                            ->label(__('filament-panels::translations.user.leader'))
                    ]),

            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->form(fn(Tables\Actions\EditAction $action): array => [
                        Toggle::make('leader')
                            ->label(__('filament-panels::translations.user.leader'))
                    ]),
                Tables\Actions\DetachAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DetachBulkAction::make(),
                ]),
            ]);
    }
}
