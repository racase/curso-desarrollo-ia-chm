<?php

namespace Database\Seeders;

use App\Models\Asset;
use App\Models\Site;
use Illuminate\Database\Seeder;

class AssetSeeder extends Seeder
{
    /** @var list<array{code: string, name: string, site: string}> */
    public const ASSETS = [
        ['code' => 'CLIMA-001', 'name' => 'Climatizador principal', 'site' => 'Sede Central'],
        ['code' => 'ASC-001', 'name' => 'Ascensor norte', 'site' => 'Sede Central'],
        ['code' => 'PUERTA-001', 'name' => 'Puerta de muelle', 'site' => 'Centro Logístico'],
    ];

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach (self::ASSETS as $asset) {
            $site = Site::query()->where('name', $asset['site'])->firstOrFail();

            Asset::withTrashed()->firstOrCreate(
                ['code' => $asset['code']],
                ['name' => $asset['name'], 'site_id' => $site->getKey()],
            );
        }
    }
}
