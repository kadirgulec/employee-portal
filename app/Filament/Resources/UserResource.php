<?php

namespace App\Filament\Resources;


use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Models\Department;
use App\Models\User;
use Exception;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Pages\PageRegistration;
use Filament\Resources\Resource;
use Filament\Support\Enums\ActionSize;
use Filament\Support\Enums\FontWeight;
use Filament\Tables;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\Layout\Stack;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Hash;

class UserResource extends Resource
{
    /**
     * Specifies the resource model
     * @var string|null
     */
    protected static ?string $model = User::class;

    /**
     * Sets the resource navigation icon
     * @var string|null
     */
    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    /**
     * specify order of the resource at navigation
     * @var int|null
     */
    protected static ?int $navigationSort = 1;


    /**
     * sets the resource name for singular cases
     * @return string
     */
    public static function getModelLabel(): string
    {
        return __('filament-panels::translations.user.single');
    }

    /**
     * sets the resource name for plural cases
     * @return string
     */
    public static function getPluralModelLabel(): string
    {
        return __('filament-panels::translations.user.plural');
    }


    /**
     * Create the form schema for the user management panel.
     *
     * This method returns a form schema that contains various components for managing
     * user-related fields like toggles for activation and illness notification contact,
     * file upload for the avatar, text inputs for personal information (e.g., name, email,
     * personal number), password management, and sections for assigning departments.
     *
     * The form includes conditional disabling of certain fields based on the user's
     * permissions, validation rules, and customized labels using translations.
     *
     * @param  Form  $form  The form instance to be configured.
     * @return Form The configured form schema for the user panel.
     */
    public static function form(Form $form): Form
    {
        return $form
            ->columns([
                'sm' => 2,
            ])
            ->schema([

                Forms\Components\Toggle::make('active')
                    ->required()
                    ->default(true)
                    ->disabled(!auth()->user()->can('backend.users.update')),

                Forms\Components\Toggle::make('illness_notification_contact')
                    ->label(__('filament-panels::translations.user.illness_notification_contact'))
                    ->required()
                    ->disabled(!auth()->user()->can('backend.users.update')),

                Forms\Components\FileUpload::make('avatar')
                    ->avatar()
                    ->directory('images/avatars')
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
                    ->dehydrateStateUsing(fn(string $state
                    ): string => Hash::make($state)) //the password will be hashed and in data bank as Hash value saved
                    ->dehydrated(fn(?string $state
                    ): bool => filled($state)) //if the password is not filled, it is not going to be hydrated, that means it is not going to hash the password.
                    ->required(fn(string $operation): bool => $operation === 'create'), //only required for create User

                Forms\Components\TextInput::make('first_name')
                    ->label(__('filament-panels::translations.user.first_name'))
                    ->required(),

                Forms\Components\TextInput::make('last_name')
                    ->label(__('filament-panels::translations.user.last_name'))
                    ->required(),

                DatePicker::make('birth_date')
                    ->label(__('filament-panels::translations.user.birth_date')),

                Forms\Components\Select::make('gender')
                    ->label(__('filament-panels::translations.user.gender'))
                    ->options(function () {
                        return [
                            'male' => __('filament-panels::translations.male'),
                            'female' => __('filament-panels::translations.female'),
                        ];
                    }),

                Forms\Components\TextInput::make('pin')
                    ->numeric()
                    ->length(4)
                    ->extraAlpineAttributes([
                        'x-model' => 'pin',
                        'x-mask' => '9999',
                    ])
                    ->dehydrateStateUsing(fn(string $state
                    ): string => Hash::make($state))
                    ->dehydrated(fn(?string $state
                    ): bool => filled($state))
                    ->visible(fn($record): bool => isset($record) && $record->id === auth()->user()->id)
                    ->required(fn($record): bool => $record->id === auth()->user()->id && is_null($record->pin)),

                Forms\Components\TextInput::make('title')
                    ->label(__('filament-panels::translations.user.title')),

                Forms\Components\TextInput::make('position')
                    ->label(__('filament-panels::translations.user.position')),

                Forms\Components\TextInput::make('phone')
                    ->label(__('filament-panels::translations.user.phone'))
                    ->tel()
                    ->telRegex('/^[+]?[0-9]{0,2}[ ]?[(]?[0-9]{1,5}[)]?[ \/\-0-9]+$/'),

                Forms\Components\TextInput::make('mobile')
                    ->label(__('filament-panels::translations.user.mobile'))
                    ->tel()
                    ->telRegex('/^[+]?[0-9]{0,2}[ ]?[(]?[0-9]{1,5}[)]?[ \/\-0-9]+$/'),

                //DEPARTMENTS
                Forms\Components\Section::make(__('filament-panels::translations.department.plural'))
                    ->disabled(!auth()->user()->can('backend.users.update'))
                    ->schema([

                        Forms\Components\Repeater::make('department_user')
                            ->defaultItems(0)
                            ->hiddenLabel()
                            ->relationship()
                            ->addActionLabel(__('filament-panels::translations.user.add_department'))
                            ->itemLabel(function (array $state): ?string {
                                $department = Department::find($state['department_id']);
                                return $department?->name;
                            })
                            ->live()
                            ->grid()
                            ->schema([

                                Select::make('department_id')
                                    ->hiddenLabel()
                                    ->native(false)
                                    ->hidden(!auth()->user()->can('backend.users.update'))
                                    ->options(function () {
                                        return Department::all()->pluck('name', 'id');
                                    }),

                                Forms\Components\Toggle::make('leader')
                                    ->label(__('filament-panels::translations.user.leader'))
                            ]),

                    ])
            ]);
    }

    /**
     * generates the user view page
     *
     * @param  Infolist  $infolist
     * @return Infolist
     */
    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->columns(4)
            ->schema([

                ImageEntry::make('avatar')
                    ->defaultImageUrl(fn($record) => asset('assets/avatars/avatar-'.$record->gender.'.png'))
                    ->hiddenLabel()
                    ->size(200)
                    ->circular()
                    ->columnSpan(1),

                Section::make()
                    ->columns([
                        'sm' => 2,
                    ])
                    ->columnSpan(3)
                    ->schema([
                        TextEntry::make('full_name')
                            ->hiddenLabel()
                            ->columnSpanFull()
                            ->size(TextEntry\TextEntrySize::Large)
                            ->weight('bold'),

                        TextEntry::make('position')
                            ->hiddenLabel()
                            ->columnSpanFull()
                            ->size(TextEntry\TextEntrySize::ExtraSmall)
                            ->extraAttributes(['style' => 'margin-top:-1.5rem']),

                        TextEntry::make('email')
                            ->hiddenLabel()
                            ->icon('heroicon-o-envelope'),

                        TextEntry::make('phone')
                            ->icon('heroicon-o-phone')
                            ->label(__('filament-panels::translations.user.phone'))
                            ->hiddenLabel(),

                        TextEntry::make('mobile')
                            ->label(__('filament-panels::translations.user.mobile'))
                            ->hiddenLabel()
                            ->icon('heroicon-o-device-phone-mobile'),

                        TextEntry::make('birth_date')
                            ->icon('heroicon-o-cake')
                            ->hiddenLabel()
                            ->label(__('filament-panels::translations.user.birth_date'))
                            ->date('d-m-Y')

                    ]),

            ]);


    }

    /**
     * generates the user list table, filters and actions
     *
     * @param  Table  $table
     * @return Table
     * @throws Exception
     */
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Stack::make([
                    ImageColumn::make('avatar')
                        ->defaultImageUrl(fn($record) => asset('assets/avatars/avatar-'.$record->gender.'.png'))
                        ->circular()
                        ->size('100%')
                        ->alignCenter()
                        ->extraImgAttributes(fn(User $record) => $record->active ? [] : [
                            'style' => 'filter: blur(4px)',
                        ]),

                    Stack::make([
                        TextColumn::make('full_name')
                            ->weight(FontWeight::Bold)
                            ->searchable([
                                'first_name', 'last_name'
                            ]),

                        TextColumn::make('position')
                            ->searchable(),
                    ]),
                ])->space(2),


            ])
            ->filters([
                SelectFilter::make('departments')
                    ->label(__('filament-panels::translations.department.plural'))
                    ->relationship('departments', 'name')
                    ->multiple()
                    ->preload(),

                Tables\Filters\TernaryFilter::make('active')
                    ->label(__('filament-panels::translations.user.active.label'))
                    ->placeholder(__('filament-panels::translations.user.active.all'))
                    ->trueLabel(__('filament-panels::translations.user.active.true'))
                    ->falseLabel(__('filament-panels::translations.user.active.false'))
                    ->queries(
                        true: fn(Builder $query) => $query->where('active', true),
                        false: fn(Builder $query) => $query->where('active', false),
                        blank: fn(Builder $query) => $query,
                    ),

                TrashedFilter::make()->visible(auth()->user()->can('backend.users.restore')),
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
                Tables\Actions\ViewAction::make()
                    ->hidden(fn(User $record) => $record->trashed()),
                Tables\Actions\EditAction::make()
                    ->hidden(fn(User $record) => $record->trashed()),
                Tables\Actions\ForceDeleteAction::make()
                    ->visible(fn(User $record) => $record->trashed()),
                Tables\Actions\RestoreAction::make()
                    ->visible(fn(User $record) => $record->trashed()),
                ActionGroup::make([
                    Tables\Actions\DeleteAction::make(),
                ])
                    ->color('gray')
                    ->size(ActionSize::Small),
            ]);
    }


    /**
     *
     * @return class-string[]
     */
    public static function getRelations(): array
    {
        return [
            RelationManagers\DepartmentsRelationManager::class,
        ];
    }


    /**
     * @return array|PageRegistration[]
     */
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'view' => Pages\ViewUser::route('/{record}'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
            'permissions' => Pages\PermissionsUser::route('/{record}/permissions'),
        ];
    }
}
