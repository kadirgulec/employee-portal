<?php

namespace App\Filament\Resources;

use App\Filament\Resources\IllnessNotificationResource\Pages;
use App\Filament\Resources\IllnessNotificationResource\RelationManagers;
use App\Models\IllnessNotification;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class IllnessNotificationResource extends Resource
{
    protected static ?string $model = IllnessNotification::class;

    protected static ?string $navigationIcon = 'heroicon-o-plus';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('user_id')
                    ->relationship('user', 'name')
                    ->required(),
                Forms\Components\TextInput::make('reported_to')
                    ->required()
                    ->numeric(),
                Forms\Components\DatePicker::make('illness_notification_at'),
                Forms\Components\DatePicker::make('doctor_visited_at'),
                Forms\Components\DateTimePicker::make('report_time')
                    ->required(),
                Forms\Components\Toggle::make('entgFG')
                    ->required(),
                Forms\Components\TextInput::make('incapacity_reason'),
                Forms\Components\TextInput::make('doctor_certificate'),
                Forms\Components\Textarea::make('note')
                    ->columnSpanFull(),
                Forms\Components\DatePicker::make('sent_at'),
                Forms\Components\TextInput::make('sent_to'),
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
                Tables\Columns\TextColumn::make('user.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('reported_to')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('illness_notification_at')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('doctor_visited_at')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('report_time')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\IconColumn::make('entgFG')
                    ->boolean(),
                Tables\Columns\TextColumn::make('incapacity_reason')
                    ->searchable(),
                Tables\Columns\TextColumn::make('doctor_certificate')
                    ->searchable(),
                Tables\Columns\TextColumn::make('sent_at')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('sent_to')
                    ->searchable(),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
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
            'index' => Pages\ListIllnessNotifications::route('/'),
            'create' => Pages\CreateIllnessNotification::route('/create'),
            'view' => Pages\ViewIllnessNotification::route('/{record}'),
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
