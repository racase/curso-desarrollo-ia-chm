<?php

namespace Tests\Feature;

use App\Enums\IncidentStatus;
use App\Exceptions\InvalidIncidentTransition;
use App\Filament\Resources\Incidents\IncidentResource;
use App\Filament\Resources\Incidents\Pages\ViewIncident;
use App\Models\Incident;
use App\Models\IncidentStatusChange;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use InvalidArgumentException;
use Livewire\Livewire;
use Tests\TestCase;

class IncidentWorkflowTest extends TestCase
{
    use RefreshDatabase;

    // Domain rules

    public function test_an_incident_goes_through_its_whole_life_cycle(): void
    {
        $tomas = User::factory()->technician()->create();
        $incident = Incident::factory()->assignedTo($tomas)->create();

        $incident->changeStatus(IncidentStatus::InProgress, $tomas);
        $incident->changeStatus(IncidentStatus::Resolved, $tomas);
        $incident->changeStatus(IncidentStatus::Closed, $tomas);

        $this->assertSame(IncidentStatus::Closed, $incident->fresh()->status);
        $this->assertSame(3, $incident->statusChanges()->count());
    }

    public function test_a_resolved_incident_can_be_reopened(): void
    {
        $tomas = User::factory()->technician()->create();
        $incident = Incident::factory()->assignedTo($tomas)->status(IncidentStatus::Resolved)->create();

        $incident->changeStatus(IncidentStatus::InProgress, $tomas);

        $this->assertSame(IncidentStatus::InProgress, $incident->fresh()->status);
    }

    public function test_an_incident_in_progress_cannot_be_closed(): void
    {
        $tomas = User::factory()->technician()->create();
        $incident = Incident::factory()->assignedTo($tomas)->status(IncidentStatus::InProgress)->create();

        try {
            $incident->changeStatus(IncidentStatus::Closed, $tomas);
            $this->fail('The transition should have been rejected.');
        } catch (InvalidIncidentTransition $exception) {
            $this->assertSame('No se puede pasar una incidencia de «En curso» a «Cerrada».', $exception->getMessage());
        }

        $this->assertSame(IncidentStatus::InProgress, $incident->fresh()->status);
        $this->assertSame(0, IncidentStatusChange::count());
    }

    public function test_a_closed_incident_cannot_change(): void
    {
        $admin = User::factory()->admin()->create();
        $incident = Incident::factory()->assignedTo(User::factory()->technician()->create())
            ->status(IncidentStatus::Closed)->create();

        $this->expectException(InvalidIncidentTransition::class);

        $incident->changeStatus(IncidentStatus::InProgress, $admin);
    }

    public function test_an_incident_without_technician_cannot_be_in_progress(): void
    {
        $admin = User::factory()->admin()->create();
        $incident = Incident::factory()->create();

        try {
            $incident->changeStatus(IncidentStatus::InProgress, $admin);
            $this->fail('The transition should have been rejected.');
        } catch (InvalidIncidentTransition $exception) {
            $this->assertSame('Asigna un técnico antes de poner la incidencia en curso.', $exception->getMessage());
        }

        $this->assertSame(IncidentStatus::Open, $incident->fresh()->status);
        $this->assertSame([], $incident->availableStatusTransitions());
    }

    public function test_only_technicians_can_be_assigned(): void
    {
        $incident = Incident::factory()->create();

        $this->expectException(InvalidArgumentException::class);

        try {
            $incident->assignTo(User::factory()->create());
        } finally {
            $this->assertNull($incident->fresh()->technician_id);
        }
    }

    public function test_each_status_change_is_recorded(): void
    {
        $tomas = User::factory()->technician()->create();
        $incident = Incident::factory()->assignedTo($tomas)->create();

        $incident->changeStatus(IncidentStatus::InProgress, $tomas);

        $change = $incident->statusChanges()->sole();
        $this->assertSame(IncidentStatus::Open, $change->from_status);
        $this->assertSame(IncidentStatus::InProgress, $change->to_status);
        $this->assertTrue($change->user->is($tomas));
        $this->assertNotNull($change->created_at);
    }

    // Authorization

    public function test_only_admins_can_assign_open_incidents(): void
    {
        $incident = Incident::factory()->create();
        $closed = Incident::factory()->status(IncidentStatus::Closed)->create();
        $admin = User::factory()->admin()->create();

        $this->assertTrue($admin->can('assign', $incident));
        $this->assertFalse($admin->can('assign', $closed));
        $this->assertFalse(User::factory()->technician()->create()->can('assign', $incident));
        $this->assertFalse(User::factory()->create()->can('assign', $incident));
    }

    public function test_only_admins_and_the_assigned_technician_can_change_the_status(): void
    {
        $tomas = User::factory()->technician()->create();
        $teresa = User::factory()->technician()->create();
        $sara = User::factory()->create();
        $incident = Incident::factory()->for($sara, 'requester')->assignedTo($tomas)->create();

        $this->assertTrue($tomas->can('changeStatus', $incident));
        $this->assertTrue(User::factory()->admin()->create()->can('changeStatus', $incident));
        $this->assertFalse($teresa->can('changeStatus', $incident));
        $this->assertFalse($sara->can('changeStatus', $incident));
    }

    // Panel

    public function test_an_admin_assigns_and_reassigns_an_incident(): void
    {
        $tomas = User::factory()->technician()->create();
        $teresa = User::factory()->technician()->create();
        $incident = Incident::factory()->create();

        $this->actingAs(User::factory()->admin()->create());

        Livewire::test(ViewIncident::class, ['record' => $incident->getRouteKey()])
            ->callAction('assign', data: ['technician_id' => $tomas->id])
            ->assertHasNoActionErrors();
        $this->assertTrue($incident->fresh()->technician->is($tomas));

        Livewire::test(ViewIncident::class, ['record' => $incident->getRouteKey()])
            ->callAction('assign', data: ['technician_id' => $teresa->id])
            ->assertHasNoActionErrors();
        $this->assertTrue($incident->fresh()->technician->is($teresa));
    }

    public function test_the_assigned_technician_changes_the_status_from_the_panel(): void
    {
        $tomas = User::factory()->technician()->create();
        $incident = Incident::factory()->assignedTo($tomas)->create();

        $this->actingAs($tomas);

        Livewire::test(ViewIncident::class, ['record' => $incident->getRouteKey()])
            ->assertActionHidden('assign')
            ->callAction('changeStatus', data: ['status' => IncidentStatus::InProgress->value])
            ->assertHasNoActionErrors();

        $this->assertSame(IncidentStatus::InProgress, $incident->fresh()->status);
    }

    public function test_the_panel_rejects_closing_an_incident_in_progress(): void
    {
        $tomas = User::factory()->technician()->create();
        $incident = Incident::factory()->assignedTo($tomas)->status(IncidentStatus::InProgress)->create();

        $this->actingAs($tomas);

        $component = Livewire::test(ViewIncident::class, ['record' => $incident->getRouteKey()])
            ->callAction('changeStatus', data: ['status' => IncidentStatus::Closed->value])
            ->assertHasActionErrors(['status']);

        $this->assertContains('El valor seleccionado en nuevo estado no es válido.', $component->errors()->all());

        $this->assertSame(IncidentStatus::InProgress, $incident->fresh()->status);
    }

    public function test_other_users_do_not_get_the_workflow_actions(): void
    {
        $tomas = User::factory()->technician()->create();
        $sara = User::factory()->create();
        $incident = Incident::factory()->for($sara, 'requester')->assignedTo($tomas)->create();

        foreach ([User::factory()->technician()->create(), $sara] as $user) {
            $this->actingAs($user);

            Livewire::test(ViewIncident::class, ['record' => $incident->getRouteKey()])
                ->assertActionHidden('assign')
                ->assertActionHidden('changeStatus');
        }
    }

    public function test_a_closed_incident_offers_no_workflow_actions(): void
    {
        $incident = Incident::factory()->assignedTo(User::factory()->technician()->create())
            ->status(IncidentStatus::Closed)->create();

        $this->actingAs(User::factory()->admin()->create());

        Livewire::test(ViewIncident::class, ['record' => $incident->getRouteKey()])
            ->assertActionHidden('assign')
            ->assertActionHidden('changeStatus');
    }

    public function test_the_requester_sees_the_technician_and_the_history_newest_first(): void
    {
        $tomas = User::factory()->technician()->create(['name' => 'Tomás Técnico']);
        $sara = User::factory()->create();
        $incident = Incident::factory()->for($sara, 'requester')->assignedTo($tomas)->create();

        $this->travelTo(now()->subHours(2));
        $incident->changeStatus(IncidentStatus::InProgress, $tomas);
        $this->travelBack();
        $incident->changeStatus(IncidentStatus::Resolved, $tomas);

        $this->assertSame(
            [IncidentStatus::Resolved, IncidentStatus::InProgress],
            $incident->statusChanges()->get()->pluck('to_status')->all(),
        );

        $this->actingAs($sara)
            ->get(IncidentResource::getUrl('view', ['record' => $incident]))
            ->assertOk()
            ->assertSee('Tomás Técnico')
            ->assertSee('Historial de estados')
            ->assertSeeInOrder(['Resuelta', 'En curso', 'Abierta']);
    }
}
