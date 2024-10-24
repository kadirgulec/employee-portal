<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $developers = [
//            'tb' => [
//                'illness_notification_contact' => true,
//                'name' => 'tb',
//                'email' => 'tb@aks-service.de',
//                'first_name' => 'Tobias',
//                'last_name' => 'Braun',
//                'gender' => 'Male',
//                'avatar' => '/01J84NXVCNHHEBGXPDQHH5AXRZ.jpg'
//            ],
//            'jp' => [
//                'illness_notification_contact' => false,
//                'name' => 'jp',
//                'email' => 'jp@aks-service.de',
//                'first_name' => 'Justin',
//                'last_name' => 'Preuß',
//                'gender' => 'Male',
//                'avatar' => '/01J84NW49V59J4ZF5EVZ8WS1PJ.jpg'
//            ],
            'kg' => [
                'illness_notification_contact' => true,
                'name' => 'kadirguelec',
                'email' => 'kg@aks-service.de',
                'first_name' => 'Kadir',
                'last_name' => 'Gülec',
                'gender' => 'Male',
                'avatar' => '/01J5Z3PDSYENPDJS3R93MGWHNR.jpg',
            ],
//            'fh' => [
//                'illness_notification_contact' => false,
//                'name' => 'fh',
//                'email' => 'fh@aks-service.de',
//                'first_name' => 'Fabian',
//                'last_name' => 'Haupt',
//                'gender' => 'Male',
//                'avatar' => '/01J84NKKM5TJ20FAHV9KA7B2P8.jpg',
//            ],
        ];

        foreach ($developers as $developer) {
            User::factory([
                'illness_notification_contact' => $developer['illness_notification_contact'],
                'name' => $developer['name'],
                'email' => $developer['email'],
                'first_name' => $developer['first_name'],
                'last_name' => $developer['last_name'],
                'gender' => $developer['gender'],
                'avatar' => $developer['avatar'],
            ])->create()->givePermissionTo(Permission::all());
        }
    }
}
