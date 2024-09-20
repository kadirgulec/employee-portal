<?php

namespace App\Filament\Resources\IllnessNotificationResource\Widgets;

use App\Models\IllnessNotification;
use App\Models\User;
use Carbon\Carbon;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make(__('filament-panels::translations.illness_notifications.today'), (function () {
                $today = Carbon::today();
                return IllnessNotification::whereDate('illness_notification_at', $today)->count();
            }))
                ->color((function () {
                    $today = Carbon::today();
                    $illUsers = IllnessNotification::whereDate('illness_notification_at', $today)->count();
                    if ($illUsers < 3) {
                        return 'success';
                    } elseif ($illUsers < 8) {
                        return 'warning';
                    } else {
                        return 'danger';
                    }
                })()),


            Stat::make('illness-notification rate', (function () {
                $today = Carbon::today();
                $illUsers = IllnessNotification::whereDate('illness_notification_at', $today)->count();
                $totalUsers = User::count();
                if ($totalUsers === 0) {
                    return '0%';
                }
                $percentage = $illUsers / $totalUsers * 100;
                return number_format($percentage, 2)."%";
            }))
                ->label(__('filament-panels::translations.illness_notifications.rate'))
                ->chart((function () {
                    $startDate = Carbon::today()->subDays(30);
                    $endDate = Carbon::today();
                    $chartData = [];

                    for ($date = $startDate; $date <= $endDate; $date->addDay()) {
                        $illUsers = IllnessNotification::whereDate('illness_notification_at', $date)->count();
                        $totalUsers = User::count();

                        $percentage = $totalUsers > 0 ? ($illUsers / $totalUsers) * 100 : 0;
                        $chartData[] = number_format($percentage, 2);
                    }

                    return $chartData;
                })())
                ->color((function () {
                    $today = Carbon::today();

                    $todayCount = IllnessNotification::query()->whereDate('illness_notification_at', $today)->count();

                    return $todayCount < 5 ? 'success' : 'danger';
                })()),


            Stat::make('Mitarbeiter', (function () {
                return User::count();
            }))
            ->label('Total ' . __('filament-panels::translations.user.plural')),

        ];
    }
}
