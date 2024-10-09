<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DepartmentResource\Pages;
use App\Filament\Resources\DepartmentResource\RelationManagers\DepartmentUsersRelationManager;
use App\Models\Department;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class DepartmentResource extends Resource
{
    protected static ?string $model = Department::class;

    protected static ?string $navigationIcon = 'heroicon-o-briefcase';

    public static function getNavigationGroup(): ?string
    {
        return __('filament-panels::translations.navigation.management');
    }


    public static function getNavigationIcon(): string
    {
        return 'heroicon-o-briefcase';
    }

    public static function getModelLabel(): string
    {
        return __('filament-panels::translations.department.single');
    }

    public static function getPluralModelLabel(): string
    {
        return __('filament-panels::translations.department.plural');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Grid::make(4)
                    ->schema([
                        Forms\Components\Section::make([
                            Forms\Components\TextInput::make('name')
                                ->required(),

                            Forms\Components\Textarea::make('description'),
                        ])
                            ->columnSpan(3),

                        Forms\Components\Section::make([
                            Forms\Components\Placeholder::make('id')
                                ->label('Department id:')
                                ->content(fn($record) => $record->id),

                            Forms\Components\Placeholder::make('created_at')
                                ->label('Created at:')
                                ->content(fn($record) => $record->created_at->toFormattedDateString()),

                            Forms\Components\Placeholder::make('updated_at')
                                ->label('Updated at:')
                                ->content(fn($record) => $record->updated_at->toFormattedDateString()),
                        ])->hidden(fn ($operation) => $operation === 'create')
                            ->extraAttributes(['class' => 'hidden lg:block'])
                            ->columnSpan(1),
                    ])

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
                Tables\Columns\TextColumn::make('name')
                    ->formatStateUsing(fn($state
                    ) => __('filament-panels::translations.department.tabs.'.str($state)->slug()->toString()))
                    ->searchable(query: function (Builder $query, string $search) {

                        $translatedSearch = __('filament-panels::translations.department.tabs.'.str($search)->slug()->toString());
//                        dd($translatedSearch);
                        return $query
                            ->where('name', 'like', "%{$search}%")
                            ->orWhere('name', 'like', "%{$translatedSearch}%");
                    }),
                Tables\Columns\TextColumn::make('description'),
                Tables\Columns\ImageColumn::make('department_users.avatar')
                    ->label(__('filament-panels::translations.department.users'))
                    ->circular()
                    ->stacked(),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            DepartmentUsersRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListDepartments::route('/'),
            'create' => Pages\CreateDepartment::route('/create'),
//            'view' => Pages\ViewDepartment::route('/{record}'),
            'edit' => Pages\EditDepartment::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
