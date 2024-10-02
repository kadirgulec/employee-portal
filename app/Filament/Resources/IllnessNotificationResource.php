<?php

namespace App\Filament\Resources;

use App\Filament\Resources\IllnessNotificationResource\Pages;
use App\Models\IllnessNotification;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class IllnessNotificationResource extends Resource
{
    protected static ?string $model = IllnessNotification::class;

    protected static ?string $navigationIcon = 'heroicon-o-plus';

    public static function getModelLabel(): string
    {
        return __('filament-panels::translations.illness_notifications.single');
    }

    public static function getPluralModelLabel(): string
    {
        return __('filament-panels::translations.illness_notifications.plural');
    }

    protected static ?int $navigationSort = 3;




    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('user_id')
                    ->label(__('filament-panels::translations.illness_notifications.user'))
                    ->relationship('user', 'id')
                    ->getOptionLabelFromRecordUsing(fn($record) => $record->first_name.' '.$record->last_name)
                    ->searchable()
                    ->preload()
                    ->required(),
                Forms\Components\Select::make('reported_to')
                    ->label(__('filament-panels::translations.illness_notifications.reported_to'))
                    ->relationship('reportedTo', 'id')
                    ->getOptionLabelFromRecordUsing(fn($record) => $record->first_name.' '.$record->last_name)
                    ->required()
                    ->preload()
                    ->searchable(),

                Forms\Components\DateTimePicker::make('report_time')
                    ->label(__('filament-panels::translations.illness_notifications.report_time'))
                    ->default(now())
                    ->required(),
                Forms\Components\DatePicker::make('illness_notification_at')
                    ->label(__('filament-panels::translations.illness_notifications.illness_notification_at'))
                    ->default(now())
                    ->required(),


                Forms\Components\DatePicker::make('doctor_visited_at')
                    ->label(__('filament-panels::translations.illness_notifications.doctor_visited_at'))
                    ->live(),

                Forms\Components\Toggle::make('entgFG')
                    ->label('§5 EntgFG')
                    ->required(fn(Get $get): bool => filled($get('doctor_visited_at')))
                    ->hidden(fn(Get $get): bool => !filled($get('doctor_visited_at'))),

                Forms\Components\Select::make('incapacity_reason')
                    ->label(__('filament-panels::translations.illness_notifications.incapacity_reason'))
                    ->options([
                        'AU wegen Krankheit' => 'AU wegen Krankheit',
                        'AU wegen Arbeitsunfall' => 'AU wegen Arbeitsunfall',
                        'AU bei stationärer Krankenhausbehandlung' => 'AU bei stationärer Krankenhausbehandlung',
                    ])
                    ->required(fn(Get $get): bool => filled($get('doctor_visited_at')))
                    ->visible(fn(Get $get): bool => filled($get('doctor_visited_at'))),

                Forms\Components\Select::make('doctor_certificate')
                    ->label(__('filament-panels::translations.illness_notifications.doctor_certificate'))
                    ->options([
                        'Erste Bescheinigung' => 'Erste Bescheinigung',
                        'Folge Bescheinigung' => 'Folge Bescheinigung',
                    ])
                    ->afterStateUpdated(function (Get $get, Set $set): void {
                        if(!filled($get('doctor_visited_at'))){
                            $set('doctor_certificate', null);                        }

                    })
                    ->required(fn(Get $get): bool => filled($get('doctor_visited_at')))
                    ->visible(fn(Get $get): bool => filled($get('doctor_visited_at'))),
                Forms\Components\Textarea::make('note')
                    ->label(__('filament-panels::translations.illness_notifications.notes'))
                    ->columnSpanFull(),
                Forms\Components\DatePicker::make('sent_at')
                ->label(__('filament-panels::translations.illness_notifications.sent_at'))
                ->hidden(!auth()->user()->can('backend.illness-notifications.update')),
                Forms\Components\TextInput::make('sent_to')
                ->label(__('filament-panels::translations.illness_notifications.sent_to'))
                    ->hidden(!auth()->user()->can('backend.illness-notifications.update')),
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
                Tables\Columns\TextColumn::make('user.full_name')
                    ->label(__('filament-panels::translations.illness_notifications.user'))
                    ->searchable([
                        'first_name',
                        'last_name',
                    ], isIndividual: true),
                Tables\Columns\TextColumn::make('reportedTo.full_name')
                    ->label(__('filament-panels::translations.illness_notifications.reported_to'))
                    ->searchable([
                        'first_name',
                        'last_name',
                    ], isIndividual: true),
                Tables\Columns\TextColumn::make('illness_notification_at')
                    ->label(__('filament-panels::translations.illness_notifications.illness_notification_at'))
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('doctor_visited_at')
                    ->label(__('filament-panels::translations.illness_notifications.doctor_visited_at'))
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('report_time')
                    ->label(__('filament-panels::translations.illness_notifications.report_time'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\IconColumn::make('entgFG')
                    ->label('§5 EnttgFG')
                    ->boolean()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('incapacity_reason')
                    ->label(__('filament-panels::translations.illness_notifications.incapacity_reason'))
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('doctor_certificate')
                    ->label(__('filament-panels::translations.illness_notifications.doctor_certificate'))
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('sent_at')
                    ->label(__('filament-panels::translations.illness_notifications.sent_at'))
                    ->date()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('sent_to')
                    ->label(__('filament-panels::translations.illness_notifications.sent_to'))
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('illness_notification_at', direction: 'desc')
            ->filters([

                Tables\Filters\Filter::make('illness_notification_at')
                    ->form([
                        DatePicker::make('illness_notification_before')
                            ->label(__('filament-panels::translations.illness_notifications.before')),
                        DatePicker::make('illness_notification_after')
                            ->label(__('filament-panels::translations.illness_notifications.after'))
                    ])
                    ->query(function (Builder $query, array $data): Builder {

                        return $query
                            ->when($data['illness_notification_before'],
                                fn(Builder $query): Builder => $query->whereDate('illness_notification_at', '<=',
                                    $data['illness_notification_before']))
                            ->when($data['illness_notification_after'],
                                fn(Builder $query): Builder => $query->whereDate('illness_notification_at', '>=',
                                    $data['illness_notification_after']));
                    }),

                Tables\Filters\TrashedFilter::make()->visible(auth()->user()->can('backend.illness-notifications.restore')),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('PDF')
                    ->label('PDF')
                    ->url(fn($record) => route("illness-notifications.pdf", $record))
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
            'index' => Pages\ListIllnessNotifications::route('/'),
            'create' => Pages\CreateIllnessNotification::route('/create'),
//            'view' => Pages\ViewIllnessNotification::route('/{record}'),
            'edit' => Pages\EditIllnessNotification::route('/{record}/edit'),
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
