<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SPProductResource\Pages;
use App\Models\SPProduct;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SPProductResource extends Resource
{
    protected static ?string $model = SPProduct::class;

    protected static ?string $navigationIcon = 'heroicon-o-wrench';

    protected static ?string $navigationGroup = 'IT Service Point';

    protected static ?int $navigationSort = 4;

    public static function getModelLabel(): string
    {
        return __('filament-panels::translations.product.single');
    }

    public static function getPluralModelLabel(): string
    {
        return __('filament-panels::translations.product.plural');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label(__('filament-panels::translations.product.name'))
                    ->required(),
                RichEditor::make('description')
                    ->label(__('filament-panels::translations.product.description'))
                    ->columnSpanFull()
                    ->toolbarButtons([
                        'bold',
                        'italic',
                        'bulletList',
                        'orderedList',
                        'blockquote',
                        'h3',
                        'redo',
                        'strike',
                        'underline',
                        'undo',
                    ]),
                Forms\Components\TextInput::make('price')
                    ->label(__('filament-panels::translations.product.price'))
                    ->required()
                    ->numeric()
                    ->prefix('€'),
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
                Tables\Columns\TextColumn::make('name')
                    ->label(__('filament-panels::translations.product.name'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('price')
                    ->label(__('filament-panels::translations.product.price'))
                    ->money('EUR')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make()->visible(auth()->user()->can('backend.sp-products.restore')),
            ])
            ->actions(
                [
                    Tables\Actions\ViewAction::make()
                        ->hidden(fn($record) => $record->trashed()),
                    Tables\Actions\EditAction::make()
                        ->hidden(fn($record) => $record->trashed()),
                    Tables\Actions\RestoreAction::make()
                        ->visible(fn($record) => $record->trashed()),
                    Tables\Actions\ForceDeleteAction::make()
                        ->visible(fn($record) => $record->trashed()),

                ]

            )
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
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSPProducts::route('/'),
            'create' => Pages\CreateSPProduct::route('/create'),
            'view' => Pages\ViewSPProduct::route('/{record}'),
            'edit' => Pages\EditSPProduct::route('/{record}/edit'),
        ];
    }


}
