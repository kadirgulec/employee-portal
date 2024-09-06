<?php

namespace Database\Factories;

use App\Models\SPProduct;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class SPProductFactory extends Factory
{
    protected $model = SPProduct::class;

    public function definition(): array
    {
        return [
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
            'name' => $this->faker->name(),
            'description' => '<p>Die Einrichtung einer automatischen und regelmäßigen Sicherheitskopie Ihres Computers oder Notebooks mithilfe einer Backup-Speicher-Software.</p><h3>Was für Sie drin ist</h3><ul><li>Beratung zu Speicher (wie Festplatte, HDD, SSD oder NAS-System)</li><li>Absicherung aller gewünschten Daten auf externen Speicher</li><li>Kopie der bestehenden Festplatte auf die neue</li><li>Aufspielen einer im Preis enthaltenen Backup-Software</li><li>Regelmäßige Durchführung automatischer Datensicherungen auf externem Datenträger</li></ul>',
            'price' => $this->faker->randomFloat(nbMaxDecimals:2 ,max: 250),
        ];
    }
}
