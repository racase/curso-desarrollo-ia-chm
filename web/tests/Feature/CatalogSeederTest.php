<?php

namespace Tests\Feature;

use App\Models\Asset;
use App\Models\Site;
use Database\Seeders\AssetSeeder;
use Database\Seeders\DatabaseSeeder;
use Database\Seeders\SiteSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CatalogSeederTest extends TestCase
{
    use RefreshDatabase;

    public function test_catalog_seeders_are_idempotent_and_link_assets_to_sites(): void
    {
        $this->seed(DatabaseSeeder::class);
        $this->seed(DatabaseSeeder::class);

        $this->assertDatabaseCount(Site::class, count(SiteSeeder::SITES));
        $this->assertDatabaseCount(Asset::class, count(AssetSeeder::ASSETS));

        foreach (Asset::query()->with('site')->get() as $asset) {
            $this->assertNotNull($asset->site);
        }
    }
}
