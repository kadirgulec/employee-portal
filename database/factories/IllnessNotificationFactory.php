<?php

namespace Database\Factories;

use App\Models\IllnessNotification;
use Illuminate\Database\Eloquent\Factories\Factory;

class IllnessNotificationFactory extends Factory
{
    protected $model = IllnessNotification::class;

    public function definition(): array
    {
        $date = fake()->dateTimeBetween('-3 months', 'now');
        return [
            'report_time'=>$date,
            'illness_notification_at' => $date,
            'reported_to' => 1,
            'incapacity_reason' => 'AU wegen Krankheit',
        ];
    }
}
