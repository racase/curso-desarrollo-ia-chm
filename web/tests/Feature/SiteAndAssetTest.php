<?php

namespace Tests\Feature;

use App\Filament\Resources\Assets\AssetResource;
use App\Filament\Resources\Assets\Pages\CreateAsset;
use App\Filament\Resources\Assets\Pages\ListAssets;
use App\Filament\Resources\Incidents\IncidentResource;
use App\Filament\Resources\Incidents\Pages\ListIncidents;
use App\Filament\Resources\Sites\Pages\CreateSite;
use App\Filament\Resources\Sites\Pages\ListSites;
use App\Filament\Resources\Sites\SiteResource;
use App\Models\Asset;
use App\Models\Incident;
use App\Models\Site;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class SiteAndAssetTest extends TestCase
{
    use RefreshDatabase;

    // Catalogue

    public function test_an_admin_creates_a_site_and_an_asset_in_it(): void
    {
        $this->actingAs(User::factory()->admin()->create());

        Livewire::test(CreateSite::class)
            ->fillForm(['name' => 'Planta Valencia'])
            ->call('create')
            ->assertHasNoFormErrors();

        $site = Site::sole();

        Livewire::test(CreateAsset::class)
            ->fillForm(['name' => 'Compresor 1', 'site_id' => $site->id])
            ->call('create')
            ->assertHasNoFormErrors();

        $asset = Asset::sole();
        $this->assertTrue($asset->site->is($site));

        Livewire::test(ListSites::class)->assertCanSeeTableRecords([$site]);
        Livewire::test(ListAssets::class)
            ->assertCanSeeTableRecords([$asset])
            ->assertTableColumnStateSet('site.name', 'Planta Valencia', $asset);
    }

    public function test_site_names_are_unique(): void
    {
        Site::factory()->create(['name' => 'Planta Valencia']);
        $this->actingAs(User::factory()->admin()->create());

        Livewire::test(CreateSite::class)
            ->fillForm(['name' => 'Planta Valencia'])
            ->call('create')
            ->assertHasFormErrors(['name' => 'unique'])
            ->assertSee('Ya existe un registro con ese valor en nombre.');

        $this->assertSame(1, Site::count());
    }

    public function test_an_asset_needs_a_site(): void
    {
        $this->actingAs(User::factory()->admin()->create());

        Livewire::test(CreateAsset::class)
            ->fillForm(['name' => 'Compresor 1', 'site_id' => null])
            ->call('create')
            ->assertHasFormErrors(['site_id' => 'required'])
            ->assertSee('El campo sede es obligatorio.');

        $this->assertSame(0, Asset::count());
    }

    public function test_the_asset_list_can_be_filtered_by_site(): void
    {
        $this->actingAs(User::factory()->admin()->create());
        $valencia = Asset::factory()->create();
        $sagunto = Asset::factory()->create();

        Livewire::test(ListAssets::class)
            ->filterTable('site', $valencia->site_id)
            ->assertCanSeeTableRecords([$valencia])
            ->assertCanNotSeeTableRecords([$sagunto]);
    }

    public function test_technicians_and_requesters_cannot_reach_the_catalogue(): void
    {
        $asset = Asset::factory()->create();

        foreach ([User::factory()->technician()->create(), User::factory()->create()] as $user) {
            $this->actingAs($user);

            $this->assertFalse(SiteResource::canViewAny());
            $this->assertFalse(AssetResource::canViewAny());

            $this->get(SiteResource::getUrl('index'))->assertForbidden();
            $this->get(SiteResource::getUrl('create'))->assertForbidden();
            $this->get(AssetResource::getUrl('index'))->assertForbidden();
            $this->get(AssetResource::getUrl('edit', ['record' => $asset]))->assertForbidden();

            $this->get('/admin')->assertOk()->assertDontSee('Catálogo');
        }
    }

    public function test_sites_and_assets_cannot_be_deleted(): void
    {
        $admin = User::factory()->admin()->create();
        $asset = Asset::factory()->create();

        $this->assertFalse($admin->can('delete', $asset));
        $this->assertFalse($admin->can('delete', $asset->site));
        $this->assertTrue($admin->can('update', $asset));
    }

    // Incidents with an asset

    public function test_the_incident_page_shows_the_asset_and_its_site(): void
    {
        $sara = User::factory()->create();
        $site = Site::factory()->create(['name' => 'Almacén Paterna']);
        $asset = Asset::factory()->for($site)->create(['name' => 'Puerta de muelle 1']);
        $incident = Incident::factory()->for($sara, 'requester')->for($asset)->create();

        $this->actingAs($sara)
            ->get(IncidentResource::getUrl('view', ['record' => $incident]))
            ->assertOk()
            ->assertSee('Puerta de muelle 1')
            ->assertSee('Almacén Paterna');
    }

    public function test_the_incident_list_can_be_filtered_by_site(): void
    {
        $this->actingAs(User::factory()->technician()->create());
        $valencia = Site::factory()->create();
        $sagunto = Site::factory()->create();
        $inValencia = Incident::factory()->for(Asset::factory()->for($valencia))->create();
        $alsoInValencia = Incident::factory()->for(Asset::factory()->for($valencia))->create();
        $inSagunto = Incident::factory()->for(Asset::factory()->for($sagunto))->create();

        Livewire::test(ListIncidents::class)
            ->filterTable('site', $valencia->id)
            ->assertCanSeeTableRecords([$inValencia, $alsoInValencia])
            ->assertCanNotSeeTableRecords([$inSagunto]);
    }

    public function test_older_incidents_without_asset_are_still_listed(): void
    {
        $this->actingAs(User::factory()->technician()->create());
        $old = Incident::factory()->withoutAsset()->create();

        Livewire::test(ListIncidents::class)
            ->assertCanSeeTableRecords([$old])
            ->assertSee('Sin activo');

        $this->get(IncidentResource::getUrl('view', ['record' => $old]))
            ->assertOk()
            ->assertSee('Sin activo');
    }

    public function test_the_seeder_creates_the_demo_sites_with_assets(): void
    {
        $this->seed(DatabaseSeeder::class);

        $this->assertEqualsCanonicalizing(
            ['Planta Valencia', 'Planta Sagunto', 'Almacén Paterna'],
            Site::pluck('name')->all(),
        );
        $this->assertTrue(Site::all()->every(fn (Site $site) => $site->assets()->count() > 1));
        $this->assertSame(0, Incident::whereNull('asset_id')->count());
    }
}
