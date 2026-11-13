<?php

namespace Database\Seeders;

use App\Models\Site;
use Illuminate\Database\Seeder;

class SiteSeeder extends Seeder
{
    /**
     * Demo sites (the same ones used by the data demo) and their assets.
     *
     * @var array<string, list<string>>
     */
    public const SITES = [
        'Planta Valencia' => [
            'Aseos planta 2',
            'Alumbrado pasillo principal',
            'Climatizador sala de reuniones',
            'Compresor 1',
        ],
        'Planta Sagunto' => [
            'Cuadro eléctrico de producción',
            'Cinta transportadora 3',
            'Caldera',
        ],
        'Almacén Paterna' => [
            'Puerta de muelle 1',
            'Carretilla elevadora 2',
            'Estanterías pasillo A',
        ],
    ];

    /**
     * Seed demo sites and assets. Safe to run more than once.
     */
    public function run(): void
    {
        foreach (self::SITES as $siteName => $assetNames) {
            $site = Site::firstOrCreate(['name' => $siteName]);

            foreach ($assetNames as $assetName) {
                $site->assets()->firstOrCreate(['name' => $assetName]);
            }
        }
    }
}
