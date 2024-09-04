<?php

namespace App\Filament\Resources\DepartmentResource\RelationManagers;

use App\Models\DepartmentUser;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class DepartmentUsersRelationManager extends RelationManager
{
    protected static string $relationship = 'department_users';


    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('full_name')
                    ->required()
                    ->options(User::all()->pluck('full_name', 'id'))
                    ->searchable()
                    ->preload(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitle(fn(User $user): string => optional($user)->full_name)
            ->columns([
                Tables\Columns\TextColumn::make('full_name'),
                Tables\Columns\ImageColumn::make('avatar')
                    ->circular(),
                Tables\Columns\IconColumn::make('leader')
                    ->icon(fn(string $state): string => match($state) {
                        '1' => 'heroicon-o-trophy',
                        '0' => 'heroicon-o-user-circle',
                    })
                    ->color(fn(string $state): string => match($state) {
                        '1' => 'success',
                        '0' => 'danger',
                    })
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\AttachAction::make()
                    ->preloadRecordSelect()
                    ->recordSelectSearchColumns(['first_name', 'last_name'])
                    ->color('primary'),
            ])
            ->actions([
//                Tables\Actions\EditAction::make(),
//                Tables\Actions\DeleteAction::make(),
                Tables\Actions\DetachAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
