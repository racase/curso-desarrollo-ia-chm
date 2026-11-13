<?php

namespace Tests\Feature;

use App\Enums\IncidentPriority;
use App\Enums\IncidentStatus;
use App\Filament\Resources\Incidents\IncidentResource;
use App\Filament\Resources\Incidents\Pages\CreateIncident;
use App\Filament\Resources\Incidents\Pages\ListIncidents;
use App\Models\Asset;
use App\Models\Incident;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class IncidentTest extends TestCase
{
    use RefreshDatabase;

    public function test_a_requester_creates_an_open_incident_as_requester(): void
    {
        $sara = User::factory()->create(['name' => 'Sara Solicitante']);
        $asset = Asset::factory()->create();
        $this->actingAs($sara);

        Livewire::test(CreateIncident::class)
            ->fillForm([
                'asset_id' => $asset->id,
                'title' => 'Fuga de agua en el baño',
                'description' => 'Gotea el grifo del lavabo.',
                'priority' => IncidentPriority::High,
            ])
            ->call('create')
            ->assertHasNoFormErrors();

        $incident = Incident::sole();
        $this->assertSame('Fuga de agua en el baño', $incident->title);
        $this->assertSame(IncidentStatus::Open, $incident->status);
        $this->assertSame(IncidentPriority::High, $incident->priority);
        $this->assertTrue($incident->requester->is($sara));
        $this->assertTrue($incident->asset->is($asset));

        Livewire::test(ListIncidents::class)
            ->assertCanSeeTableRecords([$incident]);
    }

    public function test_a_technician_creates_incidents_in_their_own_name(): void
    {
        $technician = User::factory()->technician()->create();
        $this->actingAs($technician);

        Livewire::test(CreateIncident::class)
            ->fillForm([
                'asset_id' => Asset::factory()->create()->id,
                'title' => 'Luz fundida',
                'priority' => IncidentPriority::Low,
            ])
            ->call('create')
            ->assertHasNoFormErrors();

        $this->assertTrue(Incident::sole()->requester->is($technician));
    }

    public function test_the_priority_defaults_to_medium(): void
    {
        $this->actingAs(User::factory()->create());

        Livewire::test(CreateIncident::class)
            ->assertSchemaStateSet(['priority' => IncidentPriority::Medium]);
    }

    public function test_title_priority_and_asset_are_required_with_spanish_messages(): void
    {
        $this->actingAs(User::factory()->create());

        Livewire::test(CreateIncident::class)
            ->fillForm([
                'title' => '',
                'priority' => null,
                'asset_id' => null,
            ])
            ->call('create')
            ->assertHasFormErrors(['title' => 'required', 'priority' => 'required', 'asset_id' => 'required'])
            ->assertSee('El campo título es obligatorio.')
            ->assertSee('El campo prioridad es obligatorio.')
            ->assertSee('El campo activo es obligatorio.');

        $this->assertSame(0, Incident::count());
    }

    public function test_a_requester_only_sees_their_own_incidents(): void
    {
        $sara = User::factory()->create();
        $sergio = User::factory()->create();
        $saraIncident = Incident::factory()->for($sara, 'requester')->create();
        $sergioIncident = Incident::factory()->for($sergio, 'requester')->create();

        $this->actingAs($sara);

        Livewire::test(ListIncidents::class)
            ->assertCanSeeTableRecords([$saraIncident])
            ->assertCanNotSeeTableRecords([$sergioIncident]);
    }

    public function test_a_requester_cannot_open_someone_elses_incident(): void
    {
        $sara = User::factory()->create();
        $sergioIncident = Incident::factory()->create();

        $this->actingAs($sara)
            ->get(IncidentResource::getUrl('view', ['record' => $sergioIncident]))
            ->assertNotFound();

        $this->assertFalse($sara->can('view', $sergioIncident));
    }

    public function test_a_requester_can_open_their_own_incident(): void
    {
        $sara = User::factory()->create();
        $incident = Incident::factory()->for($sara, 'requester')->create(['title' => 'Puerta del almacén']);

        $this->actingAs($sara)
            ->get(IncidentResource::getUrl('view', ['record' => $incident]))
            ->assertOk()
            ->assertSee('Puerta del almacén');
    }

    public function test_technicians_and_admins_see_every_incident(): void
    {
        $incidents = Incident::factory()->count(3)->create();

        foreach ([User::factory()->technician()->create(), User::factory()->admin()->create()] as $user) {
            $this->actingAs($user);

            Livewire::test(ListIncidents::class)
                ->assertCanSeeTableRecords($incidents)
                ->assertCountTableRecords(3);

            $this->get(IncidentResource::getUrl('view', ['record' => $incidents->first()]))->assertOk();
        }
    }

    public function test_the_list_can_be_filtered_by_status(): void
    {
        $this->actingAs(User::factory()->technician()->create());
        $open = Incident::factory()->status(IncidentStatus::Open)->create();
        $resolved = Incident::factory()->status(IncidentStatus::Resolved)->create();

        Livewire::test(ListIncidents::class)
            ->filterTable('status', IncidentStatus::Resolved->value)
            ->assertCanSeeTableRecords([$resolved])
            ->assertCanNotSeeTableRecords([$open]);
    }

    public function test_the_list_can_be_filtered_by_priority(): void
    {
        $this->actingAs(User::factory()->technician()->create());
        $low = Incident::factory()->priority(IncidentPriority::Low)->create();
        $critical = Incident::factory()->priority(IncidentPriority::Critical)->create();

        Livewire::test(ListIncidents::class)
            ->filterTable('priority', IncidentPriority::Critical->value)
            ->assertCanSeeTableRecords([$critical])
            ->assertCanNotSeeTableRecords([$low]);
    }

    public function test_the_list_shows_the_expected_columns(): void
    {
        $this->actingAs(User::factory()->technician()->create());
        Incident::factory()->create();

        Livewire::test(ListIncidents::class)
            ->assertTableColumnExists('title')
            ->assertTableColumnExists('status')
            ->assertTableColumnExists('priority')
            ->assertTableColumnExists('asset.name')
            ->assertTableColumnExists('asset.site.name')
            ->assertTableColumnExists('requester.name')
            ->assertTableColumnExists('technician.name')
            ->assertTableColumnExists('created_at');
    }

    public function test_incidents_cannot_be_edited_or_deleted(): void
    {
        $admin = User::factory()->admin()->create();
        $incident = Incident::factory()->create();

        $this->assertFalse($admin->can('update', $incident));
        $this->assertFalse($admin->can('delete', $incident));
        $this->assertFalse(IncidentResource::hasPage('edit'));
    }

    public function test_the_seeder_creates_demo_incidents(): void
    {
        $this->seed(DatabaseSeeder::class);

        $this->assertGreaterThan(0, Incident::count());
        $this->assertTrue(Incident::where('status', IncidentStatus::Open)->exists());
        $this->assertTrue(Incident::whereNotNull('technician_id')->exists());
        $this->assertTrue(Incident::has('statusChanges')->exists());
        $this->assertTrue(Incident::has('comments')->exists());
    }
}
