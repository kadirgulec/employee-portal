<?php

namespace Database\Seeders;

use App\Models\Department;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DepartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $AksDepartments =[
            'Management',
            'Technic',
            'Developer',
            'IT-Service-Point',
            'Design',
            'Administration',
        ];

        foreach ($AksDepartments as $department) {
            Department::factory()->create([
                'name' => $department,
            ]);
        }
    }
}
