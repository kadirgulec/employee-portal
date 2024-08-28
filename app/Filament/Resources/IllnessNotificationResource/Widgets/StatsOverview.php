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
            Stat::make('Krankmeldungen Heute', (function () {
                $today = Carbon::today();
                return IllnessNotification::whereDate('illness_notification_at', $today)->count();
            })),
            Stat::make('Krankmeldung rate', (function () {
                $today = Carbon::today();
                $illUsers = IllnessNotification::whereDate('illness_notification_at', $today)->count();
                $totalUsers = User::count();
                if ($totalUsers === 0) {
                    return '0%';
                }
                $percentage = $illUsers / $totalUsers * 100;
                return number_format($percentage, 2)."%";
            }))
                ->chart((function () {
                    $startDate = Carbon::today()->subDays(10);
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

                    $todayCount = IllnessNotification::whereDate('illness_notification_at', $today)->count();

                    return $todayCount < 10 ? 'success' : 'danger';
                })()),
            Stat::make('Mitarbeiter', (function () {
                 return User::count();
            }))

        ];
    }
}
