<?php

namespace App\Providers\Filament;

use App\Filament\AvatarProviders\BigUiAvatarsProvider;
use App\Filament\Resources\IllnessNotificationResource;
use App\Filament\Resources\UserResource;
use App\Http\Middleware\Pin;
use App\Http\Middleware\SetLocal;
use Filament\AvatarProviders\UiAvatarsProvider;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Navigation\MenuItem;
use Filament\Navigation\NavigationGroup;
use Filament\Pages;
use Filament\Panel;
use Filament\View\PanelsRenderHook;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Support\Facades\Blade;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->defaultAvatarProvider(UiAvatarsProvider::class)
            ->favicon('https://www.aks-service.de/wp-content/uploads/2022/10/aks_waben.png')
            ->id('admin')
            ->path('/')
            ->login()
            ->colors([
                'danger' => Color::Red,
                'gray' => Color::Slate,
                'info' => Color::Blue,
                'primary' => Color::Yellow,
                'success' => Color::Emerald,
                'warning' => Color::Orange,
            ])
            ->userMenuItems([
                'profile' => MenuItem::make()
                    ->label(__('filament-panels::translations.user.edit.profile'))
                    ->url(fn(): string => UserResource\Pages\EditUser::getUrl([auth()->user()->id]))
                    ->icon('heroicon-o-pencil-square'),
            ])
            ->navigationGroups([
                'IT Service Point',
                'Management',
            ])
            ->sidebarCollapsibleOnDesktop()
            ->brandLogo(fn() => view('filament.admin.logo'))
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                Pages\Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([
                Widgets\AccountWidget::class,
                IllnessNotificationResource\Widgets\StatsOverview::class,
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
                SetLocal::class,
                Pin::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ])
            ->spa() //single page application
            ->unsavedChangesAlerts()
            ->renderHook(
                PanelsRenderHook::USER_MENU_BEFORE,
                fn() => Blade::render('@livewire("language-switcher")'));
    }
}
