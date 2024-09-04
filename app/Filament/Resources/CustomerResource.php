<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CustomerResource\Pages;
use App\Filament\Resources\CustomerResource\RelationManagers;
use App\Filament\Resources\SPProductResource\Pages\CreateSPProduct;
use App\Models\Customer;
use App\Models\SPProduct;
use Filament\Forms;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Textarea;
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

class CustomerResource extends Resource
{
    protected static ?string $model = Customer::class;

    protected static ?string $navigationIcon = 'heroicon-o-user';

    protected static ?string $navigationGroup = 'IT Service Point';

    protected static ?string $navigationGroupIcon = 'heroicon-o-user';

    protected static ?int $navigationSort = 1;

//    public static function getModelLabel(): string
//    {
//        return 'IT Service Point';
//    }
//
//    public static function getPluralModelLabel(): string
//    {
//        return 'IT Service Point';
//    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('first_name')
                    ->required(),
                Forms\Components\TextInput::make('last_name')
                    ->required(),
                Forms\Components\TextInput::make('email')
                    ->email(),
                Forms\Components\TextInput::make('mobile')
                    ->tel(),
                Forms\Components\TextInput::make('phone')
                    ->tel(),

                Forms\Components\Section::make('Invoices')
                    ->description('The invoices for the customer')
                    ->schema([
                        Forms\Components\Repeater::make('Invoices')
                            ->addActionLabel('Add Invoice')
                            ->itemLabel(fn(array $state): ?string => $state['date'])
                            ->relationship()
                            ->schema([
                                Forms\Components\Grid::make(2)
                                    ->schema([
                                        Forms\Components\DatePicker::make('date')
                                            ->native(false)
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
                                    ]),

                                Forms\Components\Repeater::make('positions')
                                    ->addActionLabel('Add new product')
                                    ->hiddenLabel()
                                    ->extraItemActions([
                                        Action::make('Create Product')
                                            ->icon('heroicon-m-plus-circle')
                                            ->form([
                                                TextInput::make('name')
                                                    ->label('Product Name')
                                                    ->required(),
                                                Textarea::make('description')
                                                    ->label('Product Description'),
                                                TextInput::make('price')
                                                    ->label('Price')
                                                    ->numeric()
                                                    ->required(),
                                            ])
                                            ->action(function (array $data) {

                                                \App\Models\SPProduct::create($data);
                                                Notification::make()
                                                    ->title('Product Created')
                                                    ->success()
                                                    ->send();

                                            })
                                            ->modalHeading('Create New Product')
                                            ->modalWidth('lg'),
                                    ])
                                    ->relationship()
                                    ->schema([
                                        Forms\Components\Select::make('s_p_product_id')
                                            ->label('Product or Service')
                                            ->options(SPProduct::all()->pluck('name', 'id'))
                                        ->searchable()
                                        ->preload(),
                                        Forms\Components\TextInput::make('quantity')
                                            ->numeric()
                                            ->required(),
                                    ])
                                    ->live()
                                    ->grid(2)
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
                                    }),

                            ])
                            ->collapsed()
                            ->extraItemActions([
                                Forms\Components\Actions\Action::make('PDF')
                                    ->label('PDF')
                                    ->icon('heroicon-m-document-arrow-down')
                                    ->color('info')

                            ]),
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
                Tables\Columns\TextColumn::make('deleted_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('first_name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('last_name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('email')
                    ->searchable(),
                Tables\Columns\TextColumn::make('mobile')
                    ->searchable(),
                Tables\Columns\TextColumn::make('phone')
                    ->searchable(),
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
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCustomers::route('/'),
            'create' => Pages\CreateCustomer::route('/create'),
            'edit' => Pages\EditCustomer::route('/{record}/edit'),
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
