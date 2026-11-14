<?php

namespace Tests\Feature;

use App\Filament\Resources\Assets\AssetResource;
use App\Filament\Resources\Assets\Pages\CreateAsset;
use App\Filament\Resources\Assets\Pages\EditAsset;
use App\Filament\Resources\Assets\Pages\ListAssets;
use App\Models\Asset;
use App\Models\Site;
use App\Models\User;
use Filament\Actions\DeleteAction;
use Filament\Actions\Testing\TestAction;
use Filament\Facades\Filament;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class AssetResourceTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Filament::setCurrentPanel(Filament::getPanel('admin'));
    }

    public function test_an_administrator_can_open_every_asset_management_page(): void
    {
        $admin = User::factory()->admin()->create();
        $asset = Asset::factory()->create();
        $this->actingAs($admin);

        $this->get(AssetResource::getUrl('index'))->assertOk();
        $this->get(AssetResource::getUrl('create'))->assertOk();
        $this->get(AssetResource::getUrl('edit', ['record' => $asset]))->assertOk();
    }

    public function test_technicians_and_requesters_cannot_access_asset_management(): void
    {
        $asset = Asset::factory()->create();

        foreach ([User::factory()->technician()->create(), User::factory()->create()] as $user) {
            $this->actingAs($user);

            $this->get('/admin')->assertOk()->assertDontSee('Activos');
            $this->get(AssetResource::getUrl('index'))->assertForbidden();
            $this->get(AssetResource::getUrl('create'))->assertForbidden();
            $this->get(AssetResource::getUrl('edit', ['record' => $asset]))->assertForbidden();
        }
    }

    public function test_the_list_shows_active_assets_with_their_site_and_has_no_bulk_actions(): void
    {
        $admin = User::factory()->admin()->create();
        $site = Site::factory()->create(['name' => 'Sede del activo']);
        $activeAsset = Asset::factory()->for($site)->create([
            'code' => 'ACT-VISIBLE',
            'name' => 'Activo visible',
        ]);
        $deletedAsset = Asset::factory()->for($site)->create(['code' => 'ACT-DELETED']);
        $deletedAsset->delete();
        $this->actingAs($admin);

        $component = Livewire::test(ListAssets::class)
            ->assertCanSeeTableRecords([$activeAsset])
            ->assertCanNotSeeTableRecords([$deletedAsset])
            ->assertSee('ACT-VISIBLE')
            ->assertSee('Activo visible')
            ->assertSee('Sede del activo');

        $this->assertSame([], $component->instance()->getTable()->getToolbarActions());
    }

    public function test_an_administrator_can_create_and_edit_an_asset(): void
    {
        $admin = User::factory()->admin()->create();
        $firstSite = Site::factory()->create();
        $secondSite = Site::factory()->create();
        $this->actingAs($admin);

        Livewire::test(CreateAsset::class)
            ->fillForm([
                'code' => 'ACT-100',
                'name' => 'Activo nuevo',
                'site_id' => $firstSite->getKey(),
            ])
            ->call('create')
            ->assertHasNoFormErrors();

        $asset = Asset::query()->where('code', 'ACT-100')->firstOrFail();

        Livewire::test(EditAsset::class, ['record' => $asset->getRouteKey()])
            ->fillForm([
                'code' => 'ACT-101',
                'name' => 'Activo actualizado',
                'site_id' => $secondSite->getKey(),
            ])
            ->call('save')
            ->assertHasNoFormErrors();

        $this->assertDatabaseHas(Asset::class, [
            'id' => $asset->getKey(),
            'code' => 'ACT-101',
            'name' => 'Activo actualizado',
            'site_id' => $secondSite->getKey(),
        ]);
    }

    public function test_asset_forms_require_complete_unique_data(): void
    {
        $admin = User::factory()->admin()->create();
        $site = Site::factory()->create();
        $existingAsset = Asset::factory()->for($site)->create(['code' => 'ACT-EXISTING']);
        $otherAsset = Asset::factory()->for($site)->create();
        $this->actingAs($admin);

        Livewire::test(CreateAsset::class)
            ->fillForm(['code' => null, 'name' => null, 'site_id' => null])
            ->call('create')
            ->assertHasFormErrors([
                'code' => 'required',
                'name' => 'required',
                'site_id' => 'required',
            ]);

        Livewire::test(CreateAsset::class)
            ->fillForm([
                'code' => $existingAsset->code,
                'name' => 'Código repetido',
                'site_id' => $site->getKey(),
            ])
            ->call('create')
            ->assertHasFormErrors(['code' => 'unique']);

        Livewire::test(EditAsset::class, ['record' => $otherAsset->getRouteKey()])
            ->fillForm(['code' => $existingAsset->code])
            ->call('save')
            ->assertHasFormErrors(['code' => 'unique']);
    }

    public function test_asset_forms_reject_a_deleted_or_missing_site(): void
    {
        $admin = User::factory()->admin()->create();
        $deletedSite = Site::factory()->create();
        $deletedSite->delete();
        $this->actingAs($admin);

        foreach ([$deletedSite->getKey(), PHP_INT_MAX] as $siteId) {
            Livewire::test(CreateAsset::class)
                ->fillForm([
                    'code' => "ACT-{$siteId}",
                    'name' => 'Activo inválido',
                    'site_id' => $siteId,
                ])
                ->call('create')
                ->assertHasFormErrors(['site_id']);
        }
    }

    public function test_an_administrator_can_soft_delete_an_asset_from_the_list(): void
    {
        $admin = User::factory()->admin()->create();
        $asset = Asset::factory()->create();
        $this->actingAs($admin);

        Livewire::test(ListAssets::class)
            ->callAction(TestAction::make(DeleteAction::class)->table($asset))
            ->assertNotified('Activo eliminado')
            ->assertCanNotSeeTableRecords([$asset]);

        $this->assertSoftDeleted($asset);
    }
}
