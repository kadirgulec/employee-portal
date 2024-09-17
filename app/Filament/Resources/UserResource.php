<?php

namespace App\Filament\Resources;

use App\Filament\AvatarProviders\BigUiAvatarsProvider;
use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Models\Department;
use App\Models\User;
use Filament\Actions\SelectAction;
use Filament\AvatarProviders\UiAvatarsProvider;
use Filament\Forms;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Infolists\Components\Fieldset;
use Filament\Infolists\Components\Grid;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\Split;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Components\Tab;
use Filament\Resources\Resource;
use Filament\Support\Enums\ActionSize;
use Filament\Support\Enums\Alignment;
use Filament\Support\Enums\FontWeight;
use Filament\Tables;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Columns\Layout\Stack;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    protected static ?int $navigationSort = 1;


    public static function getModelLabel(): string
    {
        return __('filament-panels::translations.user.single');
    }

    public static function getPluralModelLabel(): string
    {
        return __('filament-panels::translations.user.plural');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([

                Forms\Components\Toggle::make('active')
                    ->required()
                    ->disabled(!auth()->user()->can('backend.users.update')),
                Forms\Components\Toggle::make('illness_notification_contact')
                    ->label(__('filament-panels::translations.user.illness_notification_contact'))
                    ->required()
                    ->disabled(!auth()->user()->can('backend.users.update')),
                Forms\Components\FileUpload::make('avatar')
                    ->avatar()
                    ->image()
                    ->imageEditor()
                    ->circleCropper()
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('personal_number')
                    ->label(__('filament-panels::translations.personal_number'))
                    ->numeric()
                    ->disabled(!auth()->user()->can('backend.users.update')),
                Forms\Components\TextInput::make('name')
                    ->label(__('filament-panels::translations.user.name'))
                    ->required(),
                Forms\Components\TextInput::make('email')
                    ->email()
                    ->unique(ignoreRecord: true)
                    ->required(),
                Forms\Components\TextInput::make('password')
                    ->password()
                    ->revealable()
                    ->dehydrateStateUsing(fn(string $state): string => Hash::make($state))
                    ->dehydrated(fn(?string $state): bool => filled($state))
                    ->required(fn(string $operation): bool => $operation === 'create'),
                Forms\Components\TextInput::make('first_name')
                    ->label(__('filament-panels::translations.user.first_name'))
                    ->required(),
                Forms\Components\TextInput::make('last_name')
                    ->label(__('filament-panels::translations.user.last_name'))
                    ->required(),
                Forms\Components\TextInput::make('pin')
                    ->numeric()
                    ->length(4)
                    ->required(),
                Forms\Components\Select::make('gender')
                    ->label(__('filament-panels::translations.user.gender'))
                    ->options(function () {
                        return [
                            'male' => __('filament-panels::translations.male'),
                            'female' => __('filament-panels::translations.female'),
                        ];
                    }),
                Forms\Components\TextInput::make('title')
                    ->label(__('filament-panels::translations.user.title')),
                Forms\Components\TextInput::make('position')
                    ->label(__('filament-panels::translations.user.position')),
                Forms\Components\TextInput::make('phone')
                    ->label(__('filament-panels::translations.user.phone'))
                    ->tel(),
                Forms\Components\TextInput::make('mobile')
                    ->label(__('filament-panels::translations.user.mobile'))
                    ->tel(),

//                Forms\Components\Select::make('roles')->multiple()->relationship('roles', 'name')->preload(),
//                Forms\Components\Select::make('permissions')
//                    ->label(__('filament-panels::translations.user.permissions'))
//                    ->hintAction(Action::make('select all')
//                        ->label(__('filament-panels::translations.select_all'))
//                        ->action(function ($set) {
//                            $allPermissionIds = Permission::all()->pluck('id')->toArray();
//                            $set('permissions', $allPermissionIds);
//                        }))
//                    ->multiple()
//                    ->relationship('permissions', 'name')
//                    ->preload(),

//                Forms\Components\CheckboxList::make('permissions')
//                    ->hidden(!auth()->user()->can('admin Permission'))
//                    ->label(__('filament-panels::translations.user.permissions'))
//                    ->options(Permission::all()->pluck('name', 'id'))
//                    ->hintAction(
//                        Action::make('select_all')
//                            ->label(__('filament-panels::translations.select_all'))
//                            ->action(function ($set) {
//                                $allPermissionIds = Permission::all()->pluck('id')->toArray();
//                                $set('permissions', $allPermissionIds);
//                            })
//                    )
//                    ->relationship('permissions', 'name')
//                    ->columns(4)  // Display the toggle buttons in 4 columns
//                    ->columnSpanFull(),

                Forms\Components\Section::make(__('filament-panels::translations.department.plural'))
                    ->disabled(!auth()->user()->can('backend.departments.update'))
                    ->schema([
                        Forms\Components\Repeater::make('department_user')
                            ->hiddenLabel()
                            ->relationship()
                            ->addActionLabel(__('filament-panels::translations.user.add_department'))
                            ->schema([
                                Select::make('department_id')
                                    ->hiddenLabel()
                                    ->hidden(!auth()->user()->can('backend.departments.update'))
                                    ->options(Department::all()->pluck('name', 'id')),
                                Forms\Components\Toggle::make('leader')
                                    ->label(__('filament-panels::translations.user.leader'))
                            ])
                            ->itemLabel(function (array $state): ?string {
                                $department = Department::find($state['department_id']);
                                return $department ? __('filament-panels::translations.department.tabs.'.str($department->name)->slug()->toString()) : null;
                            })
                            ->live()
                            ->grid(2),
                    ])
            ]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {

        return $infolist
            ->schema([
                Fieldset::make('Personal Information')
                    ->label(__('filament-panels::translations.personal_information'))
                    ->schema([
                        //Full Name and Avatar
                        Split::make([
                            Grid::make(1)
                                ->schema([
                                    Split::make([
                                        ImageEntry::make('avatar')
                                            ->hiddenLabel()
                                            ->defaultImageUrl(fn($record) => (new BigUiAvatarsProvider())->get($record))
                                            ->grow(false)
                                            ->circular(),
                                        Grid::make(1)
                                            ->schema([
                                                TextEntry::make('full_name')
                                                    ->hiddenLabel()
                                                    ->weight('bold'),
                                                TextEntry::make('position')
                                                    ->hiddenLabel()
                                                    ->size(TextEntry\TextEntrySize::ExtraSmall)
                                                    ->extraAttributes(['style' => 'margin-top:-1.5rem']),
                                            ]),

                                    ]),
                                    TextEntry::make('personal_number')
                                        ->label(__('filament-panels::translations.personal_number'))
                                        ->inlineLabel(),
                                ]),

                            //Other Information
                            Grid::make(1)
                                ->schema([
                                    TextEntry::make('email')
                                        ->inlineLabel(),
                                    TextEntry::make('phone')
                                        ->label(__('filament-panels::translations.user.phone'))
                                        ->inlineLabel(),
                                    TextEntry::make('mobile')
                                        ->label(__('filament-panels::translations.user.mobile'))
                                        ->inlineLabel(),
                                    TextEntry::make('birth_date')
                                        ->inlineLabel()
                                        ->label(__('filament-panels::translations.user.birth_date'))
                                        ->default(now()->format('d-m-Y')),
                                ]),
                        ])->from('md'),
                    ])->columns(1),

                Grid::make([
                    'default' => 1,
                    'sm' => 2
                ])
                    ->schema([
                        TextEntry::make('departments.name')
                            ->label(__('filament-panels::translations.department.plural'))
                            ->listWithLineBreaks(),
                        TextEntry::make('illness_notifications.illness_notification_at')
                            ->label(__('filament-panels::translations.illness_notifications.plural'))
                            ->since()
//                    ->visible(fn ($record) => auth()->user()->getKey() == $record->user_id)
                            ->listWithLineBreaks()
                            ->limitList(2)
                            ->expandableLimitedList(),
                    ])

            ]);


    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\Layout\Stack::make([
                    Tables\Columns\ImageColumn::make('avatar')
                        ->defaultImageUrl(fn($record) => (new BigUiAvatarsProvider())->get($record))->circular()
                        ->height('100%')
                        ->width('100%')
                        ->alignCenter(),

                    Tables\Columns\Layout\Stack::make([
                        Tables\Columns\TextColumn::make('full_name')
                            ->weight(FontWeight::Bold)
                            ->searchable([
                                'first_name', 'last_name'
                            ]),
                        Tables\Columns\TextColumn::make('position')
                            ->searchable(),
                    ])->configure()
                ])->space(3),


            ])
            ->filters([
                Tables\Filters\SelectFilter::make('departments')
                    ->label(__('filament-panels::translations.department.plural'))
                    ->relationship('departments', 'name')
                    ->multiple()
                    ->preload(),
                Tables\Filters\TrashedFilter::make()->visible(auth()->user()->can('backend.users.restore')),
            ])
            ->contentGrid([
                'md' => 2,
                'xl' => 3,
            ])
            ->paginated([
                6,
                12,
                18,
                36,
                72,
                'all',
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
//                Tables\Actions\BulkActionGroup::make([
//                    Tables\Actions\DeleteBulkAction::make(),
//                    Tables\Actions\ForceDeleteBulkAction::make(),
//                    Tables\Actions\RestoreBulkAction::make(),
//                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
        ];
    }


    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
//            'view' => Pages\ViewUser::route('/{record}'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
            'permissions' => Pages\PermissionsUser::route('/{record}/permissions'),
        ];
    }

//    public static function getEloquentQuery(): Builder
//    {
//        return parent::getEloquentQuery()
//            ->withoutGlobalScopes([
//                SoftDeletingScope::class,
//            ]);
//    }
}
