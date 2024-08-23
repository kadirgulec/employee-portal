<?php

namespace Database\Factories;

use App\Models\IllnessNotification;
use Illuminate\Database\Eloquent\Factories\Factory;

class IllnessNotificationFactory extends Factory
{
    protected $model = IllnessNotification::class;

    public function definition(): array
    {
        return [
            'report_time'=>fake()->dateTimeBetween('-1 years', 'now'),
            'reported_to' => 1,
            'incapacity_reason' => 'AU wegen Krankheit',
        ];
    }
}
