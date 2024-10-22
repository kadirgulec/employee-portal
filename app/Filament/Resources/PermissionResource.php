<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PermissionResource\Pages;
use App\Filament\Resources\PermissionResource\RelationManagers;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use App\Models\Permission;

class PermissionResource extends Resource
{
    protected static ?string $model = Permission::class;

    protected static ?string $navigationIcon = 'heroicon-o-key';

    protected static ?int $navigationSort = 5;

    public static function getNavigationGroup(): ?string
    {
        return __('filament-panels::translations.navigation.management');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->disabled(),
                Forms\Components\TextInput::make('guard_name')
                    ->disabled(),
//                Forms\Components\Repeater::make('users')
//                    ->relationship('users')
//                    ->live()
//                    ->columnSpanFull()
//                    ->grid(2)
//                    ->schema([
//                        Forms\Components\Placeholder::make('full_name')
//                            ->label(fn($state) => $state)
//                            ->key('full_name')
//                            ->hintActions([
//                                Action::make('remove_permission')
//                                    ->requiresConfirmation()
//                                    ->action(function ($record, $livewire, $state) {
//                                        dd($record);
//                                        $permission = Permission::find($record->permission_id);
//                                        $record->revokePermissionTo($permission);
//                                        $livewire->redirect('/permissions/'.$record->permission_id);
//                                        Notification::make()
//                                            ->title('Removed successfully')
//                                            ->success()
//                                            ->send();
//                                    }),
//                                Action::make('edit_user')
//                                    ->action(function ($record, $livewire) {
//                                        $livewire->redirect('/users/'.$record->id.'/edit');
//                                    })
//
//                            ]),
//                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\Layout\Split::make([
                    Tables\Columns\Layout\Stack::make([
                        Tables\Columns\TextColumn::make('name')
                            ->searchable()
                            ->sortable(),
                        Tables\Columns\TextColumn::make('guard_name')
                            ->searchable()
                            ->sortable(),
                    ]),
                    Tables\Columns\ImageColumn::make('users.avatar')
                        ->circular()
                        ->wrap()
                        ->stacked()
                        ->visibleFrom('xl'),
                ]),

            ])
            ->filters([

            ])
            ->actions([

            ])
            ->bulkActions([

            ]);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\UsersRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPermissions::route('/'),
//            'create' => Pages\CreatePermission::route('/create'),
//            'view' => Pages\ViewPermission::route('/{record}'),
            'edit' => Pages\EditPermission::route('/{record}/edit'),
        ];
    }



}
