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

    protected static ?string $navigationIcon = 'heroicon-o-paper-clip';

    protected static ?int $navigationSort = 2;

    public static function getModelLabel(): string
    {
        return __('filament-panels::translations.work-instruction.single');
    }

    /**
     * sets the resource name for plural cases
     * @return string
     */
    public static function getPluralModelLabel(): string
    {
        return __('filament-panels::translations.work-instruction.plural');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('title')
                    ->label(__('filament-panels::translations.work-instruction.title'))
                    ->required()
                    ->maxLength(255)
                    ->disabled(function ($record, $operation) {
                        if ($operation === 'create') {
                            return false;
                        }
                        return $record->users()->wherePivotNotNull('confirmed_at')->exists()
                            || $record->users()->wherePivotNotNull('rejection_reason')->exists();
                    }),
                Forms\Components\Textarea::make('description')
                    ->label(__('filament-panels::translations.work-instruction.description'))
                    ->columnSpanFull()
                    ->disabled(function ($record, $operation) {
                        if ($operation === 'create') {
                            return false;
                        }
                        return $record->users()->wherePivotNotNull('confirmed_at')->exists()
                            || $record->users()->wherePivotNotNull('rejection_reason')->exists();
                    }),
                Forms\Components\FileUpload::make('document')
                    ->label(__('filament-panels::translations.work-instruction.document'))
                    ->directory('work-instructions')
                    ->disabled(function ($record, $operation) {
                        if ($operation === 'create') {
                            return false;
                        }
                        return $record->users()->wherePivotNotNull('confirmed_at')->exists()
                            || $record->users()->wherePivotNotNull('rejection_reason')->exists();
                    }),
                Forms\Components\View::make('filament.show-work-instruction-document')
                    ->columnSpanFull()
                    ->visible(fn($record) => !is_null($record->document)),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(function (WorkInstruction $record) {
                if (auth()->user()->can('backend.work-instructions.update')) {
                    return parent::getEloquentQuery();
                } else {
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
            })
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
                    ->formatStateUsing(fn($state
                    ) => __('filament-panels::translations.work-instruction.status.'.$state))
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
