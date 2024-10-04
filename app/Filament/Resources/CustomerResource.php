<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CustomerResource\Pages;
use App\Models\Bill;
use App\Models\Customer;
use App\Models\SPProduct;
use App\Policies\BillPolicy;
use Filament\Forms\Components\Textarea;
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
use Illuminate\Support\Facades\Gate;


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
                    ->tel()
                    ->telRegex('/^[+]?[0-9]{0,2}[ ]?[(]?[0-9]{1,5}[)]?[ \/\-0-9]+$/'),
                Forms\Components\TextInput::make('phone')
                    ->label(__('filament-panels::translations.customer.phone'))
                    ->tel()
                    ->telRegex('/^[+]?[0-9]{0,2}[ ]?[(]?[0-9]{1,5}[)]?[ \/\-0-9]+$/'),

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
                                fn(Action $action
                                ) => $action->requiresConfirmation()->visible(auth()->user()->can('backend.bills.delete'))
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
                                    ->visible(fn($state, $arguments) => isset($state[$arguments['item']]['id']))
                                    ->icon('heroicon-m-document-arrow-down')
                                    ->color('info')
                                    ->openUrlInNewTab()
                                    ->button()
                                    ->labeledFrom('md')
                                    ->outlined(),
                                Action::make('completed')
                                    ->label(__('filament-panels::translations.bill.completed'))
                                    ->icon('heroicon-s-check')
                                    ->color(function($state, $arguments) {
                                        $billId = $state[$arguments['item']]['id'];
                                        $bill = Bill::find($billId);

                                        if($bill->status !== 'completed'){
                                            return 'gray';
                                        }else{
                                            return 'success';
                                        }
                                    })
                                    ->action(function ($state, $arguments) {
                                        $billId = $state[$arguments['item']]['id'];
                                        $bill = Bill::find($billId);
                                        $bill->update([
                                            'status' => 'completed',
                                        ]);
                                    }),

                            ])
                            ->schema([
                                //two text fields at top of the Bill repeater
                                Forms\Components\Grid::make(2)
                                    ->disabled(fn($record) => Gate::denies('update', $record) && isset($record))
                                    ->schema([
                                        Forms\Components\DatePicker::make('date')
                                            ->label(__('filament-panels::translations.bill.date'))
                                            ->native(false)
                                            ->required(),

                                        TextInput::make('cost_approval')
                                            ->label(__('filament-panels::translations.bill.cost_approval'))
                                            ->numeric(),

                                        Forms\Components\Select::make('payment_method')
                                            ->label(__('filament-panels::translations.bill.payment_method'))
                                            ->options([
                                                'Bei Abholung' => 'Bei Abholung',
                                                'Bar' => 'Bar',
                                                'Karte' => 'Karte',
                                            ]),

                                        Textarea::make('comment')
                                            ->label(__('filament-panels::translations.bill.comment'))
                                            ->rows(4),

                                        Textarea::make('device_info')
                                            ->label(__('filament-panels::translations.bill.device_info'))
                                            ->rows(4),

                                        Textarea::make('device_condition')
                                            ->label(__('filament-panels::translations.bill.device_condition'))
                                            ->rows(4),

                                        TextInput::make('device_password')
                                            ->label(__('filament-panels::translations.bill.device_password')),

                                        Forms\Components\TextInput::make('total_price')
                                            ->label(__('filament-panels::translations.bill.total_price'))
                                            ->numeric()
                                            ->readOnly()
                                            ->prefix('€')
                                            ->dehydrated(false)
                                            ->afterStateHydrated(fn($get, $set) => BillResource::setTotalPrice($get,
                                                $set)),
                                    ]),

                                //Positions repeater under Bill repeater
                                Forms\Components\Repeater::make('positions')
                                    ->addActionLabel(__('filament-panels::translations.product.add'))
                                    ->hiddenLabel()
                                    ->disabled(fn($record) => Gate::denies('update', $record) && isset($record))
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
                                        SPProductResource::actionCreateNewProduct(),
                                    ])
                                    ->deleteAction(
                                        fn(Action $action, $record) => $action->requiresConfirmation()
                                            ->visible(auth()->user()->can('backend.bills.update') || auth()->user()->id == optional($record)->created_by),
                                    )
                                    ->afterStateUpdated(fn($get, $set) => BillResource::setTotalPrice($get, $set))
                                    ->afterStateHydrated(fn($get, $set) => BillResource::setTotalPrice($get, $set)),
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
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('last_name')
                    ->label(__('filament-panels::translations.customer.last_name'))
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('email')
                    ->label(__('filament-panels::translations.customer.email'))
                    ->searchable()
                    ->sortable(),
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

                Tables\Actions\ViewAction::make()
                    ->hidden(fn($record) => $record->trashed()),
                Tables\Actions\EditAction::make()
                    ->hidden(fn($record) => $record->trashed()),
                ActionGroup::make([
                    Tables\Actions\DeleteAction::make(),
                ])
                    ->color('gray')
                    ->size(ActionSize::Small),
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
