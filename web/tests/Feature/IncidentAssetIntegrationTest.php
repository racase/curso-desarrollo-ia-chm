<?php

namespace Tests\Feature;

use App\Enums\IncidentPriority;
use App\Enums\IncidentStatus;
use App\Filament\Resources\Incidents\Pages\CreateIncident;
use App\Filament\Resources\Incidents\Pages\ListIncidents;
use App\Filament\Resources\Incidents\Pages\ViewIncident;
use App\Models\Asset;
use App\Models\Incident;
use App\Models\Site;
use App\Models\User;
use App\Services\IncidentWorkflow;
use Filament\Facades\Filament;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class IncidentAssetIntegrationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Filament::setCurrentPanel(Filament::getPanel('admin'));
    }

    public function test_a_requester_can_create_an_incident_for_an_active_asset(): void
    {
        $requester = User::factory()->create();
        $asset = Asset::factory()->create();
        $this->actingAs($requester);

        Livewire::test(CreateIncident::class)
            ->fillForm([
                'title' => 'Avería vinculada',
                'description' => 'La avería afecta al activo seleccionado.',
                'priority' => IncidentPriority::High->value,
                'asset_id' => $asset->getKey(),
            ])
            ->call('create')
            ->assertHasNoFormErrors();

        $incident = Incident::query()->where('title', 'Avería vinculada')->firstOrFail();

        $this->assertTrue($incident->requester->is($requester));
        $this->assertTrue($incident->asset->is($asset));
    }

    public function test_incident_creation_rejects_unavailable_assets(): void
    {
        $requester = User::factory()->create();
        $deletedAsset = Asset::factory()->create();
        $deletedAsset->delete();
        $assetAtDeletedSite = Asset::factory()->create();
        $assetAtDeletedSite->site->delete();
        $this->actingAs($requester);

        foreach ([$deletedAsset->getKey(), $assetAtDeletedSite->getKey(), PHP_INT_MAX] as $index => $assetId) {
            Livewire::test(CreateIncident::class)
                ->fillForm([
                    'title' => "Avería no válida {$index}",
                    'description' => 'Este alta debe rechazarse.',
                    'priority' => IncidentPriority::Medium->value,
                    'asset_id' => $assetId,
                ])
                ->call('create')
                ->assertHasFormErrors(['asset_id']);
        }

        $this->assertDatabaseCount(Incident::class, 0);
    }

    public function test_authorized_users_see_historical_asset_and_site_context(): void
    {
        $requester = User::factory()->create();
        $technician = User::factory()->technician()->create();
        $admin = User::factory()->admin()->create();
        $site = Site::factory()->create(['name' => 'Sede Histórica']);
        $asset = Asset::factory()->for($site)->create([
            'code' => 'HIST-900',
            'name' => 'Generador histórico',
        ]);
        $incident = Incident::factory()
            ->forRequester($requester)
            ->forAsset($asset)
            ->create();

        $this->assertUsersSeeIncidentContext([$requester, $technician, $admin], $incident);

        $asset->delete();
        $site->delete();

        $this->assertUsersSeeIncidentContext([$requester, $technician, $admin], $incident);
    }

    public function test_controlled_workflow_changes_do_not_change_the_incident_asset(): void
    {
        $admin = User::factory()->admin()->create();
        $technician = User::factory()->technician()->create();
        $asset = Asset::factory()->create();
        $incident = Incident::factory()->forAsset($asset)->create();
        $workflow = app(IncidentWorkflow::class);

        $workflow->assignTechnician($admin, $incident, $technician);
        $workflow->transition($technician, $incident, IncidentStatus::InProgress);

        $incident->refresh();

        $this->assertSame($asset->getKey(), $incident->asset_id);
        $this->assertTrue($incident->asset->is($asset));
    }

    public function test_legacy_incidents_without_an_asset_remain_visible(): void
    {
        $requester = User::factory()->create();
        $incident = Incident::factory()
            ->forRequester($requester)
            ->create(['asset_id' => null]);
        $this->actingAs($requester);

        Livewire::test(ListIncidents::class)
            ->assertCanSeeTableRecords([$incident])
            ->assertSee('Sin activo');

        Livewire::test(ViewIncident::class, ['record' => $incident->getRouteKey()])
            ->assertSee('Sin activo');
    }

    /** @param list<User> $users */
    private function assertUsersSeeIncidentContext(array $users, Incident $incident): void
    {
        foreach ($users as $user) {
            $this->actingAs($user);

            Livewire::test(ListIncidents::class)
                ->assertCanSeeTableRecords([$incident])
                ->assertSee('HIST-900')
                ->assertSee('Generador histórico')
                ->assertSee('Sede Histórica');

            Livewire::test(ViewIncident::class, ['record' => $incident->getRouteKey()])
                ->assertSee('HIST-900')
                ->assertSee('Generador histórico')
                ->assertSee('Sede Histórica');
        }
    }
}
