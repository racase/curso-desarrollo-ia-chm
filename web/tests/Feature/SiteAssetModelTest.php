<?php

namespace Tests\Feature;

use App\Models\Asset;
use App\Models\Incident;
use App\Models\Site;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SiteAssetModelTest extends TestCase
{
    use RefreshDatabase;

    public function test_sites_and_assets_persist_their_relationships(): void
    {
        $site = Site::factory()->create([
            'name' => 'Edificio Norte',
            'address' => 'Calle Norte, 10',
        ]);
        $assets = Asset::factory()->count(2)->for($site)->create();

        $this->assertSame('Edificio Norte', $site->name);
        $this->assertSame('Calle Norte, 10', $site->address);
        $this->assertCount(2, $site->assets);
        $this->assertTrue($assets->every(fn (Asset $asset): bool => $asset->site->is($site)));
    }

    public function test_soft_deleted_catalog_records_are_hidden_but_keep_incident_context(): void
    {
        $site = Site::factory()->create(['name' => 'Sede histórica']);
        $asset = Asset::factory()->for($site)->create([
            'code' => 'HIST-001',
            'name' => 'Equipo histórico',
        ]);
        $incident = Incident::factory()->forAsset($asset)->create();

        $asset->delete();
        $site->delete();

        $this->assertNull(Asset::query()->find($asset->getKey()));
        $this->assertNull(Site::query()->find($site->getKey()));
        $this->assertSoftDeleted($asset);
        $this->assertSoftDeleted($site);
        $this->assertSame('Equipo histórico', $incident->fresh()->asset->name);
        $this->assertSame('Sede histórica', $incident->fresh()->asset->site->name);
    }

    public function test_a_legacy_incident_can_exist_without_an_asset(): void
    {
        $incident = Incident::factory()->create(['asset_id' => null]);

        $this->assertNull($incident->asset_id);
        $this->assertNull($incident->asset);
    }
}
