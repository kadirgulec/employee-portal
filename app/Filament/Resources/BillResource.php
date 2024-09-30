<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BillResource\Pages;
use App\Models\Bill;
use App\Models\Customer;
use App\Models\SPProduct;
use Filament\Forms;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class BillResource extends Resource
{
    protected static ?string $model = Bill::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static ?string $navigationGroup = 'IT Service Point';

    protected static ?int $navigationSort = 3;

    public static function getModelLabel(): string
    {
        return __('filament-panels::translations.bill.single');
    }

    public static function getPluralModelLabel(): string
    {
        return __('filament-panels::translations.bill.plural');
    }


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\DatePicker::make('date')
                    ->label(__('filament-panels::translations.bill.date'))
                    ->required()
                    ->native(false),
                Forms\Components\Select::make('customer_id')
                    ->label(__('filament-panels::translations.bill.customer'))
                    ->relationship('customer', 'id')
                    ->options(Customer::all()->pluck('full_name', 'id'))
                    ->searchable()
                    ->preload()
                    ->required(),

                Forms\Components\TextInput::make('total_price')
                    ->label(__('filament-panels::translations.bill.total_price'))
                    ->numeric()
                    ->readOnly()
                    ->prefix('€')
                    ->afterStateHydrated(fn($get, $set) => self::setTotalPrice($get, $set)),


                Forms\Components\Repeater::make('positions')
                    ->addActionLabel(__('filament-panels::translations.product.add'))
                    ->itemLabel(fn(array $state): ?string => $state['product_name'] ?? null)
                    ->hiddenLabel()
                    ->relationship()
                    ->columnSpanFull()
                    ->live()
                    ->afterStateUpdated(fn($get, $set) => self::setTotalPrice($get, $set))
                    ->afterStateHydrated(fn($get, $set) => self::setTotalPrice($get, $set))
                    ->grid()
                    ->schema([
                        Forms\Components\Select::make('s_p_product_id')
                            ->label(__('filament-panels::translations.product.name'))
                            ->options(SPProduct::all()->pluck('name', 'id'))
                            ->searchable()
                            ->preload()
                            ->live()
                            ->required()
                            ->afterStateUpdated(function (Get $get, Set $set) {
                                $product = SPProduct::find($get('s_p_product_id'));
                                $set('product_description', optional($product)->description);
                                $set('product_price', optional($product)->price);
                                $set('product_name', optional($product)->name);
                            }),
                        Forms\Components\TextInput::make('quantity')
                            ->label(__('filament-panels::translations.bill.quantity'))
                            ->numeric()
                            ->required(),
                        Forms\Components\TextInput::make('product_name')
                            ->label(__('filament-panels::translations.bill.product_name')),
                        RichEditor::make('product_description')
                            ->label(__('filament-panels::translations.bill.product_description'))
                            ->columnSpanFull()
                            ->toolbarButtons([
                                'bold',
                                'italic',
                                'bulletList',
                                'orderedList',
                                'h3',
                                'redo',
                                'strike',
                                'underline',
                                'undo',
                            ]),
                        TextInput::make('product_price')
                            ->label(__('filament-panels::translations.bill.unit_price'))
                            ->numeric(),
                    ])
                    ->extraItemActions([
                        Action::make('Create new Product')
                            ->label(__('filament-panels::translations.product.create_new'))
                            ->icon('heroicon-m-plus-circle')
                            ->visible(auth()->user()->can('backend.sp-products.create'))
                            ->form([
                                TextInput::make('name')
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
                                        'h3',
                                        'redo',
                                        'strike',
                                        'underline',
                                        'undo',
                                    ]),
                                TextInput::make('price')
                                    ->label(__('filament-panels::translations.product.price'))
                                    ->numeric()
                                    ->required(),
                            ])
                            ->action(function (array $data, SPProduct $product, $set) {

                                $product->create($data);

                                Notification::make()
                                    ->title(__('filament-panels::translations.product.notify_created'))
                                    ->success()
                                    ->send();
                            })
                            ->color('success')
                            ->modalHeading(__('filament-panels::translations.product.create'))
                            ->modalWidth('lg')
                    ])
                    ,


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
                Tables\Columns\TextColumn::make('date')
                    ->label(__('filament-panels::translations.bill.date'))
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('customer.full_name')
                    ->label(__('filament-panels::translations.bill.customer'))
                    ->numeric()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make()->hidden(fn($record) => $record->trashed()),
                Tables\Actions\EditAction::make()->hidden(fn($record) => $record->trashed()),
                Tables\Actions\Action::make('PDF')
                    ->hidden(fn($record) => $record->trashed())
                    ->label('PDF')
                    ->url(fn($record): string => route('bill.pdf', $record))
                    ->color('info')
                    ->icon('heroicon-o-document-arrow-down')
                    ->outlined()
                    ->openUrlInNewTab(),

                Tables\Actions\RestoreAction::make()
                    ->visible(fn($record) => $record->trashed()),
                Tables\Actions\ForceDeleteAction::make()
                    ->visible(fn($record) => $record->trashed()),

            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
                ]),
            ]);
    }

    public static function setTotalPrice(Get $get, Set $set): void
    {
        $positions = $get('positions') ?? [];


        $totalPrice = collect($positions)->sum(function ($position) {
            if (isset($position['product_price']) && is_numeric($position['quantity'])) {
                return $position['quantity'] * $position['product_price'];
            } else {
                return 0;
            }

        });
        $set('total_price', $totalPrice);
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
            'index' => Pages\ListBills::route('/'),
            'create' => Pages\CreateBill::route('/create'),
            'view' => Pages\ViewBill::route('/{record}'),
            'edit' => Pages\EditBill::route('/{record}/edit'),
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
