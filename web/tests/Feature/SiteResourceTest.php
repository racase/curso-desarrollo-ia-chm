<?php

namespace Tests\Feature;

use App\Filament\Resources\Sites\Pages\CreateSite;
use App\Filament\Resources\Sites\Pages\EditSite;
use App\Filament\Resources\Sites\Pages\ListSites;
use App\Filament\Resources\Sites\SiteResource;
use App\Models\Asset;
use App\Models\Site;
use App\Models\User;
use Filament\Actions\DeleteAction;
use Filament\Actions\Testing\TestAction;
use Filament\Facades\Filament;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class SiteResourceTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Filament::setCurrentPanel(Filament::getPanel('admin'));
    }

    public function test_an_administrator_can_open_every_site_management_page(): void
    {
        $admin = User::factory()->admin()->create();
        $site = Site::factory()->create();
        $this->actingAs($admin);

        $this->get(SiteResource::getUrl('index'))->assertOk();
        $this->get(SiteResource::getUrl('create'))->assertOk();
        $this->get(SiteResource::getUrl('edit', ['record' => $site]))->assertOk();
    }

    public function test_technicians_and_requesters_cannot_access_site_management(): void
    {
        $site = Site::factory()->create();

        foreach ([User::factory()->technician()->create(), User::factory()->create()] as $user) {
            $this->actingAs($user);

            $this->get('/admin')->assertOk()->assertDontSee('Sedes');
            $this->get(SiteResource::getUrl('index'))->assertForbidden();
            $this->get(SiteResource::getUrl('create'))->assertForbidden();
            $this->get(SiteResource::getUrl('edit', ['record' => $site]))->assertForbidden();
        }
    }

    public function test_the_list_shows_active_sites_and_has_no_bulk_actions(): void
    {
        $admin = User::factory()->admin()->create();
        $activeSite = Site::factory()->create([
            'name' => 'Sede visible',
            'address' => 'Calle Visible, 2',
        ]);
        $deletedSite = Site::factory()->create(['name' => 'Sede eliminada']);
        $deletedSite->delete();
        $this->actingAs($admin);

        $component = Livewire::test(ListSites::class)
            ->assertCanSeeTableRecords([$activeSite])
            ->assertCanNotSeeTableRecords([$deletedSite])
            ->assertSee('Sede visible')
            ->assertSee('Calle Visible, 2');

        $this->assertSame([], $component->instance()->getTable()->getToolbarActions());
    }

    public function test_an_administrator_can_create_and_edit_a_site(): void
    {
        $admin = User::factory()->admin()->create();
        $this->actingAs($admin);

        Livewire::test(CreateSite::class)
            ->fillForm(['name' => 'Nueva sede', 'address' => null])
            ->call('create')
            ->assertHasNoFormErrors();

        $site = Site::query()->where('name', 'Nueva sede')->firstOrFail();

        $this->assertNull($site->address);

        Livewire::test(EditSite::class, ['record' => $site->getRouteKey()])
            ->fillForm(['name' => 'Sede actualizada', 'address' => 'Calle Actualizada, 5'])
            ->call('save')
            ->assertHasNoFormErrors();

        $this->assertDatabaseHas(Site::class, [
            'id' => $site->getKey(),
            'name' => 'Sede actualizada',
            'address' => 'Calle Actualizada, 5',
        ]);
    }

    public function test_site_forms_require_a_unique_name(): void
    {
        $admin = User::factory()->admin()->create();
        $existingSite = Site::factory()->create(['name' => 'Sede existente']);
        $otherSite = Site::factory()->create();
        $this->actingAs($admin);

        Livewire::test(CreateSite::class)
            ->fillForm(['name' => null])
            ->call('create')
            ->assertHasFormErrors(['name' => 'required']);

        Livewire::test(CreateSite::class)
            ->fillForm(['name' => $existingSite->name])
            ->call('create')
            ->assertHasFormErrors(['name' => 'unique']);

        Livewire::test(EditSite::class, ['record' => $otherSite->getRouteKey()])
            ->fillForm(['name' => $existingSite->name])
            ->call('save')
            ->assertHasFormErrors(['name' => 'unique']);
    }

    public function test_site_deletion_is_blocked_until_active_assets_are_removed(): void
    {
        $admin = User::factory()->admin()->create();
        $site = Site::factory()->create();
        $asset = Asset::factory()->for($site)->create();
        $this->actingAs($admin);

        Livewire::test(ListSites::class)
            ->callAction(TestAction::make(DeleteAction::class)->table($site))
            ->assertNotified('No se puede eliminar la sede')
            ->assertCanSeeTableRecords([$site]);

        $this->assertNotSoftDeleted($site);

        $asset->delete();

        Livewire::test(ListSites::class)
            ->callAction(TestAction::make(DeleteAction::class)->table($site))
            ->assertNotified('Sede eliminada')
            ->assertCanNotSeeTableRecords([$site]);

        $this->assertSoftDeleted($site);
    }
}
