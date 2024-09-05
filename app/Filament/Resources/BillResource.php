<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BillResource\Pages;
use App\Filament\Resources\BillResource\RelationManagers;
use App\Models\Bill;
use App\Models\Customer;
use App\Models\SPProduct;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
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


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\DatePicker::make('date')
                    ->required(),
                Forms\Components\Select::make('customer_id')
                    ->relationship('customer', 'id')
                    ->options(Customer::all()->pluck('full_name', 'id'))
                    ->searchable()
                    ->preload()
                    ->required(),

                Forms\Components\TextInput::make('total_price')
                    ->numeric()
                    ->readOnly()
                    ->prefix('€')
                    ->afterStateHydrated(function (Get $get, Set $set) {
                        $positions = $get('positions') ?? [];
                        $totalPrice = collect($positions)->sum(function ($position) {

                            if ($position['s_p_product_id'] && is_numeric($position['quantity'])) {
                                return $position['quantity'] * SPProduct::find($position['s_p_product_id'])->price;
                            } else {
                                return 0;
                            }
                        });
                        $set('total_price', $totalPrice);
                    }),


                Forms\Components\Repeater::make('positions')
                    ->addActionLabel('Add new product')
                    ->hiddenLabel()
                    ->relationship()
                    ->columnSpanFull()
                    ->schema([
                        Forms\Components\Select::make('s_p_product_id')
                            ->label('Product or Service')
                            ->options(SPProduct::all()->pluck('name', 'id')),
                        Forms\Components\TextInput::make('quantity')
                            ->numeric()
                            ->required(),
                    ])
                    ->live()
                    ->afterStateUpdated(function (Get $get, Set $set) {
                        $positions = $get('positions') ?? [];
                        $totalPrice = collect($positions)->sum(function ($position) {
                            if ($position['s_p_product_id'] && is_numeric($position['quantity'])) {
                                return $position['quantity'] * SPProduct::find($position['s_p_product_id'])->price;
                            } else {
                                return 0;
                            }

                        });
                        $set('total_price', $totalPrice);
                    })
                    ->afterStateHydrated(function (Get $get, Set $set) {
                        $positions = $get('positions') ?? [];
                        $totalPrice = collect($positions)->sum(function ($position) {

                            if ($position['s_p_product_id'] && is_numeric($position['quantity'])) {
                                return $position['quantity'] * SPProduct::find($position['s_p_product_id'])->price;
                            } else {
                                return 0;
                            }
                        });
                        $set('total_price', $totalPrice);
                    })
                ->grid(2),


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
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('customer.full_name')
                    ->numeric()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('PDF')
                    ->label('PDF')
                    ->url("/")
                    ->color('info')
                    ->openUrlInNewTab(),

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
