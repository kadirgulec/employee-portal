<?php

namespace Database\Seeders;

use App\Models\SPProduct;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SPProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $titles =
            [
                'Displaytausch am Laptop / Tastaturtausch am Laptop',
                'Display Reperatur iPad / Tablet',
                'Laptop und PC Reinigung',
                'Virententfernung',
                'Datenübertragung',
                'Datensicherung',
                'Datenrettung',
                'Antivierenschutz',
                'Hardware-Check',
            ];

        foreach ($titles as $title) {
            SPProduct::factory()->create([
                'name' => $title
            ]);
        }

    }
}
