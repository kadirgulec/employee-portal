<?php

namespace App\Filament\Resources;

use App\Filament\Resources\WorkInstructionResource\Pages;
use App\Filament\Resources\WorkInstructionResource\RelationManagers;
use App\Filament\Resources\WorkInstructionResource\RelationManagers\GroupsRelationManager;
use App\Filament\Resources\WorkInstructionResource\RelationManagers\UsersRelationManager;
use App\Models\WorkInstruction;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class WorkInstructionResource extends Resource
{
    protected static ?string $model = WorkInstruction::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';


    public static function getEloquentQuery(): Builder
    {
        if(auth()->user()->can('backend.work-instructions.update')) {
            return parent::getEloquentQuery();
        }else{
            return parent::getEloquentQuery()
                ->whereHas('users', function (Builder $query) {
                    $query->where('user_id', auth()->user()->id);
                })
                ->orWhereHas('groups', function (Builder $query) {
                    $query->whereHas('users', function (Builder $query) {
                        $query->where('user_id', auth()->user()->id);
                    });
                });
        }
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('title')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Textarea::make('description')
                    ->columnSpanFull(),
                Forms\Components\FileUpload::make('document'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('deleted_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('title')
                    ->searchable(),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'new' => 'primary',
                        'confirmed' => 'success',
                        'rejected' => 'gray',
                        'updated' => 'info',
                        'waiting' => 'warning',
                        'warning' => 'danger'
                    }),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            GroupsRelationManager::class,
            UsersRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListWorkInstructions::route('/'),
            'create' => Pages\CreateWorkInstruction::route('/create'),
            'view' => Pages\ViewWorkInstruction::route('/{record}'),
            'edit' => Pages\EditWorkInstruction::route('/{record}/edit'),
        ];
    }
}
