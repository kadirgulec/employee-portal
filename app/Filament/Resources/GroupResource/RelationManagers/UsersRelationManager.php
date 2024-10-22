<?php

namespace App\Filament\Resources\GroupResource\RelationManagers;

use App\Models\User;
use Filament\Forms;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class UsersRelationManager extends RelationManager
{
    protected static string $relationship = 'users';


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
                    ->modalHeading(__('filament-panels::translations.department.add_user'))
                    ->preloadRecordSelect()
                    ->recordSelectSearchColumns(['first_name', 'last_name'])
                    ->color('primary')
                    ,
            ])
            ->actions([
                Tables\Actions\DetachAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DetachBulkAction::make(),
                ]),
            ]);
    }
}
