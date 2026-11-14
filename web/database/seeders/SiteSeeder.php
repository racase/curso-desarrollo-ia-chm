<?php

namespace Database\Seeders;

use App\Models\Site;
use Illuminate\Database\Seeder;

class SiteSeeder extends Seeder
{
    /** @var list<array{name: string, address: string}> */
    public const SITES = [
        ['name' => 'Sede Central', 'address' => 'Calle Mayor, 1'],
        ['name' => 'Centro Logístico', 'address' => 'Avenida de la Industria, 25'],
    ];

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach (self::SITES as $site) {
            Site::withTrashed()->firstOrCreate(
                ['name' => $site['name']],
                ['address' => $site['address']],
            );
        }
    }
}
