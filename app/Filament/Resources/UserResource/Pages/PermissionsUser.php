<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use Filament\Actions;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\Pages\EditRecord;
use Spatie\Permission\Models\Permission;


class PermissionsUser extends EditRecord
{
    protected static string $resource = UserResource::class;

    public function getBreadcrumb(): string
    {
        return static::$breadcrumb ?? __('filament-panels::translations.user.permissions');
    }

    protected function authorizeAccess(): void
    {
        abort_unless(auth()->user()->can('backend.users.permissions'), 403);
    }

    public function getTitle(): string
    {
        return __('filament-panels::permissions.title');

    }


    public function form(Form $form): Form
    {
        return $form
            ->schema(
                function () {
                    $schema = [];

                    $permissions = [];

                    Permission::all()
                        ->map(function ($permission) use (&$permissions) {
                            $parts = explode('.', $permission->name);

                            $permissions[$parts[0]][$parts[1]][$parts[2]] = $permission;
                        });

                    foreach ($permissions as $key0 => $level0) {
                        $schema[] = Section::make(__('filament-panels::permissions.'.$key0.'.title'))
                            ->schema(
                                function () use ($level0, $key0) {
                                    $schemaLevel1 = [];
                                    foreach ($level0 as $key1 => $level1) {
                                        $schemaLevel1[] = Fieldset::make(__('filament-panels::permissions.'.$key0.'.'.$key1.'.title'))
                                            ->schema(function () use ($level1) {
                                                $schemaLevel2 = [];
                                                foreach ($level1 as $key2 => $permission) {

                                                    $schemaLevel2[] = Toggle::make($permission)
                                                        ->label(__('filament-panels::permissions.'.$permission->name.'.title'))
                                                        ->helperText(__('filament-panels::permissions.'.$permission->name.'.description'))
                                                        ->onColor('success')
                                                        ->offColor('danger')
                                                        ->onIcon('heroicon-m-bolt')
                                                        ->offIcon('heroicon-m-bolt-slash')
                                                        ->formatStateUsing(fn($record
                                                        ) => $record->can($permission->name))
                                                        ->afterStateUpdated(function ($record, $state) use ($permission
                                                        ) {
                                                            if ($state) {
                                                                $record->givePermissionTo($permission->name);

                                                            } else {
                                                                $record->revokePermissionTo($permission->name);
                                                            };
                                                        })
                                                        ->live();
                                                }
                                                return $schemaLevel2;
                                            }
                                            );
                                    }
                                    return $schemaLevel1;
                                }

                            );

                    }

                    return $schema;

                }
            );
    }

    public function getFormActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
