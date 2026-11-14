<?php

namespace Tests\Feature;

use App\Models\Asset;
use App\Models\Incident;
use App\Models\Site;
use App\Models\User;
use App\Services\CatalogManagementService;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class CatalogManagementServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_an_administrator_can_create_and_update_catalog_records(): void
    {
        $admin = User::factory()->admin()->create();
        $service = app(CatalogManagementService::class);
        $site = $service->createSite($admin, [
            'name' => 'Sede inicial',
            'address' => 'Dirección inicial',
        ]);
        $asset = $service->createAsset($admin, [
            'code' => 'ACT-001',
            'name' => 'Activo inicial',
            'site_id' => $site->getKey(),
        ]);

        $service->updateSite($admin, $site, ['address' => 'Dirección actualizada']);
        $service->updateAsset($admin, $asset, ['name' => 'Activo actualizado']);

        $this->assertSame('Dirección actualizada', $site->refresh()->address);
        $this->assertSame('Activo actualizado', $asset->refresh()->name);
        $this->assertTrue($asset->site->is($site));
    }

    public function test_the_service_enforces_catalog_authorization(): void
    {
        $this->expectException(AuthorizationException::class);

        app(CatalogManagementService::class)->createSite(
            User::factory()->create(),
            ['name' => 'Sede bloqueada'],
        );
    }

    public function test_an_asset_cannot_reference_a_missing_or_deleted_site(): void
    {
        $admin = User::factory()->admin()->create();
        $service = app(CatalogManagementService::class);
        $deletedSite = Site::factory()->create();
        $deletedSite->delete();

        try {
            $service->createAsset($admin, [
                'code' => 'ACT-MISSING',
                'name' => 'Activo sin sede',
                'site_id' => PHP_INT_MAX,
            ]);
            $this->fail('An asset was linked to a missing site.');
        } catch (ValidationException $exception) {
            $this->assertArrayHasKey('site_id', $exception->errors());
        }

        try {
            $service->createAsset($admin, [
                'code' => 'ACT-002',
                'name' => 'Activo inválido',
                'site_id' => $deletedSite->getKey(),
            ]);
            $this->fail('An asset was linked to a deleted site.');
        } catch (ValidationException $exception) {
            $this->assertArrayHasKey('site_id', $exception->errors());
        }

        $asset = Asset::factory()->create();

        try {
            $service->updateAsset($admin, $asset, ['site_id' => $deletedSite->getKey()]);
            $this->fail('An asset was moved to a deleted site.');
        } catch (ValidationException $exception) {
            $this->assertArrayHasKey('site_id', $exception->errors());
        }

        $this->assertDatabaseMissing(Asset::class, ['code' => 'ACT-MISSING']);
        $this->assertDatabaseMissing(Asset::class, ['code' => 'ACT-002']);
        $this->assertNotSame($deletedSite->getKey(), $asset->refresh()->site_id);
    }

    public function test_a_site_with_active_assets_cannot_be_deleted(): void
    {
        $admin = User::factory()->admin()->create();
        $site = Site::factory()->create();
        Asset::factory()->for($site)->create();

        try {
            app(CatalogManagementService::class)->deleteSite($admin, $site);
            $this->fail('A site with active assets was deleted.');
        } catch (ValidationException $exception) {
            $this->assertArrayHasKey('delete', $exception->errors());
        }

        $this->assertNotSoftDeleted($site);
    }

    public function test_allowed_deletions_preserve_historical_incident_relationships(): void
    {
        $admin = User::factory()->admin()->create();
        $site = Site::factory()->create(['name' => 'Sede conservada']);
        $asset = Asset::factory()->for($site)->create(['name' => 'Activo conservado']);
        $incident = Incident::factory()->forAsset($asset)->create();
        $service = app(CatalogManagementService::class);

        $service->deleteAsset($admin, $asset);
        $service->deleteSite($admin, $site);

        $this->assertSoftDeleted($asset);
        $this->assertSoftDeleted($site);
        $this->assertSame('Activo conservado', $incident->fresh()->asset->name);
        $this->assertSame('Sede conservada', $incident->fresh()->asset->site->name);
    }
}
