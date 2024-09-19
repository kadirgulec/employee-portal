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

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            PermissionSeeder::class,
            UserSeeder::class,
            DepartmentSeeder::class,
            UserDepartmentIllnessNotificationSeeder::class,

            SPProductSeeder::class,
            CustomerSeeder::class,
        ]);


    }
}
