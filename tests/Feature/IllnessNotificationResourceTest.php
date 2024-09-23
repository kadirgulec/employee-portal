<?php

use App\Filament\Resources\IllnessNotificationResource\Pages\ViewIllnessNotification;
use App\Filament\Resources\IllnessNotificationResource\Widgets\StatsOverview;
use App\Models\IllnessNotification;
use App\Models\User;
use function Pest\Livewire\livewire;

it('can view Illness Notification', function () {

    $user = User::factory()
        ->has(IllnessNotification::factory()->count(3), 'illness_notifications')
        ->create();

    livewire(ViewIllnessNotification::class, [
        'record' => IllnessNotification::first()->getRouteKey()
    ])
        ->assertSee($user->first_name);
});

it('can download Illness Notification PDF', function () {
    $user = User::factory()
        ->has(IllnessNotification::factory()->count(3), 'illness_notifications')
        ->create();

    $this->get('illness-notifications/'.IllnessNotification::first()->id.'/pdf')
        ->assertDownload();
});

it('can view Illness Notifications widgets', function () {
    livewire(StatsOverview::class)
        ->assertSee(IllnessNotification::count());

    User::factory()
        ->has(IllnessNotification::factory([
            'illness_notification_at' => now(),
        ])->count(4), 'illness_notifications')
        ->create();

    livewire(StatsOverview::class)
        ->assertSee(IllnessNotification::count());

    User::factory()
        ->has(IllnessNotification::factory([
            'illness_notification_at' => now(),
        ])->count(5), 'illness_notifications')
        ->create();

    livewire(StatsOverview::class)
        ->assertSee(IllnessNotification::count());


});
