<?php

namespace Database\Seeders;

use App\Models\Department;
use App\Models\IllnessNotification;
use App\Models\Permission;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{

    protected array $AksDepartments =[
        'Management',
        'Technic',
        'Developer',
        'IT-Service-Point',
        'Design',
        'Administration',
    ];
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        Permission::create(['name' => 'create User']);
        Permission::create(['name' => 'admin Permission']);
        User::factory()->create([
            'id' => 1,
            'illness_notification_contact' => true,
            'name' => 'kadirguelec',
            'first_name' => 'Kadir',
            'last_name' => 'Gülec',
            'email' => 'kg@aks-service.de',
            'gender' => 'Male',
        ])->givePermissionTo(['create User', 'admin Permission']);


        foreach ($this->AksDepartments as $department) {
            Department::factory()->create([
                'name' => $department,
            ]);
        }

        //create users with 3 illness notification each and a department
        $departments = Department::all();
        foreach ($departments as $department) {
            for ($i = 0; $i <= 20; $i++) {
                $user = User::factory()
                    ->has(IllnessNotification::factory(), 'illness_notifications')
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
