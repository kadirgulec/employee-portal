<?php

namespace Database\Seeders;

use App\Models\Department;
use App\Models\IllnessNotification;
use App\Models\User;
use Illuminate\Database\Seeder;

class UserDepartmentIllnessNotificationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //create users with 3 illness notification each and a department
        $departments = Department::all();
        foreach ($departments as $department) {
            for ($i = 0; $i <= 10; $i++) {
                $user = User::factory()
                    ->has(IllnessNotification::factory()->count(3), 'illness_notifications')
                    ->create();


                $user->department_user()->create([
                    'department_id' => $department->id,
                    'user_id' => $user->id,
                    'leader' => false,
                ]);
            }

        }
    }
}
