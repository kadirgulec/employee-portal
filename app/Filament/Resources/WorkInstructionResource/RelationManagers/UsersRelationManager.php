<?php

namespace App\Filament\Resources\WorkInstructionResource\RelationManagers;

use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class UsersRelationManager extends RelationManager
{
    protected static string $relationship = 'users';

    public static function getTitle(Model $ownerRecord, string $pageClass): string
    {
        return __('filament-panels::translations.user.plural');
    }
    public static function getBadge(Model $ownerRecord, string $pageClass): ?string
    {
        return $ownerRecord->users->count();
    }

    public static function canViewForRecord(Model $ownerRecord, string $pageClass): bool
    {
        return auth()->user()->can('backend.work-instructions.update');
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitle(fn(User $user): string => optional($user)->full_name)
            ->columns([
                Tables\Columns\TextColumn::make('full_name')
                ->label(__('filament-panels::translations.user.full_name')),
                Tables\Columns\TextColumn::make('confirmed_at')
                ->label(__('filament-panels::translations.user.confirmed_at')),
                TextColumn::make('rejection_reason')
                ->label(__('filament-panels::translations.user.rejection_reason')),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\AttachAction::make()
                    ->modalHeading(__('filament-panels::translations.department.add_user'))
                    ->preloadRecordSelect()
                    ->recordSelectSearchColumns(['first_name', 'last_name'])
                    ->color('primary'),
            ])
            ->actions([
                Tables\Actions\DetachAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DetachBulkAction::make(),
                ]),
            ]);
    }
}
