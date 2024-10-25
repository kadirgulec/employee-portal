<?php

namespace App\Filament\Resources\WorkInstructionResource\Pages;

use App\Filament\Resources\WorkInstructionResource;
use App\Models\WorkInstruction;
use Closure;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Get;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ViewRecord;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;

class ViewWorkInstruction extends ViewRecord
{
    protected static string $resource = WorkInstructionResource::class;


    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),

            Action::make('confirm')
                ->hidden(fn(WorkInstruction $record) => $this->canBeConfirmedOrRejected($record))
                ->form([
                    TextInput::make('pin')
                        ->mask('9999')
                        ->required()
                        ->rules([
                            fn(): Closure => function (string $attribute, $value, Closure $fail) {
                                if (!Hash::check($value, auth()->user()->pin)) {
                                    $fail('The :attribute is invalid.');
                                }
                            },
                        ])
                ])
                ->action(function (WorkInstruction $record) {

                    $userId = auth()->user()->id;

                    if ($record->users()->wherePivot('user_id', $userId)->exists()) {
                        $workInstruction = $record->users()->wherePivot('user_id', $userId);
                        $workInstruction->update([
                            'confirmed_at' => now(),
                        ]);
                    } else {
                        $record->users()->attach($userId, [
                            'created_at' => now(),
                            'confirmed_at' => now(),
                        ]);
                    }
                })
                ->color('success'),

            Action::make('reject')
                ->hidden(fn(WorkInstruction $record) => $this->canBeConfirmedOrRejected($record))
                ->form([
                    Textarea::make('reason')
                        ->required()
                        ->minLength(50),
                ])
                ->action(function (array $data, WorkInstruction $record) {
                    $userId = auth()->user()->id;

                    if (!$record->users()->wherePivot('user_id', $userId)->exists()) {
                        $record->users()->attach($userId, [
                            'created_at' => now(),
                            'rejection_reason' => $data['reason'],
                        ]);
                    }
                })
                ->color('danger'),
        ];
    }

    protected function canBeConfirmedOrRejected($record): bool
    {
        $userId = auth()->user()->id;

        $hasConfirmedOrRejected = $record->users()
            ->wherePivotNotNull('confirmed_at')
            ->orwherePivotNotNull('rejection_reason')
            ->exists();

        $userInGroup = $record->groups()->whereHas('users', function (Builder $query) use ($userId) {
            $query->where('user_id', $userId);
        })->exists();


        $userAssociatedWithInstruction = $record->users()->where('users.id', $userId)->exists();

        return $hasConfirmedOrRejected
            || (!$userInGroup && !$userAssociatedWithInstruction)
            || ($userAssociatedWithInstruction && $hasConfirmedOrRejected);
    }


}
