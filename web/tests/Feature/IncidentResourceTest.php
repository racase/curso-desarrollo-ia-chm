<?php

namespace Tests\Feature;

use App\Enums\IncidentPriority;
use App\Enums\IncidentStatus;
use App\Filament\Resources\Incidents\IncidentResource;
use App\Filament\Resources\Incidents\Pages\CreateIncident;
use App\Filament\Resources\Incidents\Pages\ListIncidents;
use App\Filament\Resources\Incidents\Pages\ViewIncident;
use App\Models\Incident;
use App\Models\User;
use Filament\Facades\Filament;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class IncidentResourceTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Filament::setCurrentPanel(Filament::getPanel('admin'));
    }

    public function test_a_requester_can_create_an_incident_with_the_required_data(): void
    {
        $requester = User::factory()->create();
        $this->actingAs($requester);

        Livewire::test(CreateIncident::class)
            ->fillForm([
                'title' => 'Aire acondicionado averiado',
                'description' => 'El equipo no enfría desde esta mañana.',
                'priority' => IncidentPriority::High->value,
            ])
            ->call('create')
            ->assertHasNoFormErrors();

        $this->assertDatabaseHas(Incident::class, [
            'title' => 'Aire acondicionado averiado',
            'description' => 'El equipo no enfría desde esta mañana.',
            'status' => IncidentStatus::Open->value,
            'priority' => IncidentPriority::High->value,
            'requester_id' => $requester->getKey(),
        ]);
    }

    public function test_the_create_form_requires_title_description_and_priority(): void
    {
        $this->actingAs(User::factory()->create());

        Livewire::test(CreateIncident::class)
            ->fillForm([
                'title' => null,
                'description' => null,
                'priority' => null,
            ])
            ->call('create')
            ->assertHasFormErrors([
                'title' => 'required',
                'description' => 'required',
                'priority' => 'required',
            ]);
    }

    public function test_technicians_and_administrators_cannot_open_the_create_page(): void
    {
        $users = [
            User::factory()->technician()->create(),
            User::factory()->admin()->create(),
        ];

        foreach ($users as $user) {
            $this->actingAs($user)
                ->get(IncidentResource::getUrl('create'))
                ->assertForbidden();
        }
    }

    public function test_a_requester_only_sees_their_incidents_in_the_resource(): void
    {
        $requester = User::factory()->create();
        $ownIncident = Incident::factory()->forRequester($requester)->create();
        $otherIncident = Incident::factory()->create();
        $this->actingAs($requester);

        Livewire::test(ListIncidents::class)
            ->assertCanSeeTableRecords([$ownIncident])
            ->assertCanNotSeeTableRecords([$otherIncident]);

        $this->get(IncidentResource::getUrl('view', ['record' => $ownIncident]))
            ->assertOk();
        $this->get(IncidentResource::getUrl('view', ['record' => $otherIncident]))
            ->assertNotFound();
    }

    public function test_technicians_and_administrators_see_every_incident_in_the_resource(): void
    {
        $incidents = Incident::factory()->count(3)->create();
        $users = [
            User::factory()->technician()->create(),
            User::factory()->admin()->create(),
        ];

        foreach ($users as $user) {
            $this->actingAs($user);

            Livewire::test(ListIncidents::class)
                ->assertCanSeeTableRecords($incidents);
        }
    }

    public function test_incidents_can_be_filtered_by_status_and_priority(): void
    {
        $technician = User::factory()->technician()->create();
        $openLow = Incident::factory()
            ->status(IncidentStatus::Open)
            ->priority(IncidentPriority::Low)
            ->create();
        $openHigh = Incident::factory()
            ->status(IncidentStatus::Open)
            ->priority(IncidentPriority::High)
            ->create();
        $resolvedHigh = Incident::factory()
            ->status(IncidentStatus::Resolved)
            ->priority(IncidentPriority::High)
            ->create();
        $this->actingAs($technician);

        Livewire::test(ListIncidents::class)
            ->filterTable('status', IncidentStatus::Open->value)
            ->assertCanSeeTableRecords([$openLow, $openHigh])
            ->assertCanNotSeeTableRecords([$resolvedHigh]);

        Livewire::test(ListIncidents::class)
            ->filterTable('priority', IncidentPriority::High->value)
            ->assertCanSeeTableRecords([$openHigh, $resolvedHigh])
            ->assertCanNotSeeTableRecords([$openLow]);

        Livewire::test(ListIncidents::class)
            ->filterTable('status', IncidentStatus::Open->value)
            ->filterTable('priority', IncidentPriority::High->value)
            ->assertCanSeeTableRecords([$openHigh])
            ->assertCanNotSeeTableRecords([$openLow, $resolvedHigh]);
    }

    public function test_the_resource_has_no_edit_or_delete_actions(): void
    {
        $requester = User::factory()->create();
        $incident = Incident::factory()->forRequester($requester)->create();
        $this->actingAs($requester);

        $this->assertArrayNotHasKey('edit', IncidentResource::getPages());

        Livewire::test(ViewIncident::class, ['record' => $incident->getRouteKey()])
            ->assertActionDoesNotExist('edit')
            ->assertActionDoesNotExist('delete');

        $this->get("/admin/incidents/{$incident->getRouteKey()}/edit")
            ->assertNotFound();
    }
}
