<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CustomerResource\Pages;
use App\Models\Customer;
use App\Models\SPProduct;
use Filament\Tables\Actions\ActionGroup;
use Filament\Forms;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Support\Enums\ActionSize;
use Filament\Tables;
use Filament\Tables\Table;


class CustomerResource extends Resource
{
    protected static ?string $model = Customer::class;

    protected static ?string $navigationIcon = 'heroicon-o-user';

    protected static ?string $navigationGroup = 'IT Service Point';

    protected static ?string $navigationGroupIcon = 'heroicon-o-user';

    protected static ?int $navigationSort = 1;

    public static function getModelLabel(): string
    {
        return __('filament-panels::translations.customer.single');
    }

    public static function getPluralModelLabel(): string
    {
        return __('filament-panels::translations.customer.plural');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('first_name')
                    ->label(__('filament-panels::translations.customer.first_name'))
                    ->required(),
                Forms\Components\TextInput::make('last_name')
                    ->label(__('filament-panels::translations.customer.last_name'))
                    ->required(),
                TextInput::make('address')
                    ->label(__('filament-panels::translations.customer.address'))
                    ->nullable(),
                TextInput::make('city')
                    ->label(__('filament-panels::translations.customer.city')),
                Forms\Components\TextInput::make('email')
                    ->label(__('filament-panels::translations.customer.email'))
                    ->unique(ignoreRecord: true)
                    ->email(),
                Forms\Components\TextInput::make('mobile')
                    ->label(__('filament-panels::translations.customer.mobile'))
                    ->tel(),
                Forms\Components\TextInput::make('phone')
                    ->label(__('filament-panels::translations.customer.phone'))
                    ->tel(),

                //Bills section
                Forms\Components\Section::make(__('filament-panels::translations.bill.plural'))
                    ->description(__('filament-panels::translations.bill.description'))
                    ->schema([
                        Forms\Components\Repeater::make('bills')
                            ->addActionLabel(__('filament-panels::translations.bill.add'))
                            ->disabled(!auth()->user()->can('backend.bills.create'))
                            ->itemLabel(fn(array $state): ?string => date('d.m.Y', strtotime($state['date'])))
                            ->relationship('bills', fn($query) => $query->orderByDesc('date'))
                            ->collapsed()
                            ->defaultItems(0)
                            ->deleteAction(
                                fn(Action $action) => $action->requiresConfirmation()->visible(auth()->user()->can('backend.bills.delete'))
                            )
                            ->extraItemActions([
                                Forms\Components\Actions\Action::make('PDF')
                                    ->label('PDF')
                                    ->url(function ($state, array $arguments) {
                                        if (isset($state[$arguments['item']]['id'])) {
                                            $itemID = $state[$arguments['item']]['id'];
                                            return route('bill.pdf', $itemID);
                                        }

                                        return null;


                                    })
                                    ->icon('heroicon-m-document-arrow-down')
                                    ->color('info')
                                    ->openUrlInNewTab()
                                    ->button()
                                    ->labeledFrom('md')
                                    ->outlined()

                            ])
                            ->schema([
                                //two text fields at top of the Bill repeater
                                Forms\Components\Grid::make(2)
                                    ->schema([
                                        Forms\Components\DatePicker::make('date')
                                            ->label(__('filament-panels::translations.bill.date'))
                                            ->native(false)
                                            ->required(),
                                        Forms\Components\TextInput::make('total_price')
                                            ->label(__('filament-panels::translations.bill.total_price'))
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

                                //Positions repeater under Bill repeater
                                Forms\Components\Repeater::make('positions')
                                    ->addActionLabel(__('filament-panels::translations.product.add'))
                                    ->hiddenLabel()
                                    //disable the add Product when the user is not authorised to update or not created the bill
                                    ->disabled(fn($record) => !auth()->user()->can('backend.bills.update') && auth()->user()->id != optional($record)->created_by && isset($record))
                                    ->itemLabel(fn(array $state): ?string => $state['product_name'])
                                    ->live()
                                    ->collapsible()
                                    ->grid(2)
                                    ->relationship('positions')
                                    ->schema([
                                        //select a product field
                                        Forms\Components\Select::make('s_p_product_id')
                                            ->label(__('filament-panels::translations.bill.product'))
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
                                        TextInput::make('product_name')
                                            ->label(__('filament-panels::translations.bill.product_name')),
                                        RichEditor::make('product_description')
                                            ->label(__('filament-panels::translations.bill.product_description'))
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
                                                        'blockquote',
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
                                    ->deleteAction(
                                        fn(Action $action, $record) => $action->requiresConfirmation()
                                            ->visible(auth()->user()->can('backend.bills.update') || auth()->user()->id == optional($record)->created_by ),
                                    )
                                    ->afterStateUpdated(function (Get $get, Set $set) {
                                        $positions = $get('positions') ?? [];
                                        $totalPrice = collect($positions)->sum(function ($position) {
                                            if ($position['s_p_product_id'] && is_numeric($position['quantity']) && is_numeric($position['product_price'])) {
                                                return $position['quantity'] * $position['product_price'];
                                            } else {
                                                return 0;
                                            }

                                        });
                                        $set('total_price', $totalPrice);
                                    })
                                    ->afterStateHydrated(function (Get $get, Set $set) {
                                        $positions = $get('positions') ?? [];
                                        $totalPrice = collect($positions)->sum(function ($position) {

                                            if ($position['s_p_product_id'] && is_numeric($position['quantity']) && is_numeric($position['product_price'])) {
                                                return $position['quantity'] * $position['product_price'] ?? 0;
                                            } else {
                                                return 0;
                                            }
                                        });
                                        $set('total_price', $totalPrice);
                                    }),
                            ])

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
                    ->label(__('filament-panels::translations.customer.first_name'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('last_name')
                    ->label(__('filament-panels::translations.customer.last_name'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('email')
                    ->label(__('filament-panels::translations.customer.email'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('mobile')
                    ->label(__('filament-panels::translations.customer.mobile'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('phone')
                    ->label(__('filament-panels::translations.customer.phone'))
                    ->searchable(),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make()->visible(auth()->user()->can('backend.customers.restore')),
            ])
            ->actions([

                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                ActionGroup::make([
                    Tables\Actions\DeleteAction::make(),
                    Tables\Actions\ForceDeleteAction::make(),
                    Tables\Actions\RestoreAction::make(),
                ])
                    ->color('gray')
                    ->size(ActionSize::Small),

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

//    public static function getEloquentQuery(): Builder
//    {
////        return parent::getEloquentQuery()
////            ->withoutGlobalScopes([
////                SoftDeletingScope::class,
////            ]);
//    }
}
