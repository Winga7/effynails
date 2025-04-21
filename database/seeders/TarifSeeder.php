<?php

namespace Database\Seeders;

use App\Models\Tarif;
use Illuminate\Database\Seeder;

class TarifSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tarifs = [
            [
                'nom' => 'Manucure simple',
                'prix' => 25.00,
                'duree' => 30,
                'description' => 'Soin des ongles et des cuticules',
            ],
            [
                'nom' => 'Pose de gel',
                'prix' => 40.00,
                'duree' => 60,
                'description' => 'Application de gel avec finition brillante',
            ],
            [
                'nom' => 'Pédicure complète',
                'prix' => 35.00,
                'duree' => 45,
                'description' => 'Soin complet des pieds et ongles',
            ],
        ];

        foreach ($tarifs as $tarif) {
            Tarif::create($tarif);
        }
    }
} 