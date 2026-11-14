<?php

namespace Tests\Feature;

use App\Enums\IncidentStatus;
use App\Models\Incident;
use App\Models\IncidentStatusChange;
use App\Models\User;
use App\Services\IncidentWorkflow;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class IncidentTrackingTest extends TestCase
{
    use RefreshDatabase;

    public function test_tracking_relations_casts_and_foreign_keys_are_configured(): void
    {
        $technician = User::factory()->technician()->create();
        $actor = User::factory()->admin()->create();
        $incident = Incident::factory()->assignedTo($technician)->create();
        $statusChange = IncidentStatusChange::factory()->create([
            'incident_id' => $incident,
            'changed_by_id' => $actor,
        ]);

        $this->assertTrue($incident->assignedTechnician->is($technician));
        $this->assertTrue($technician->assignedIncidents->contains($incident));
        $this->assertTrue($statusChange->incident->is($incident));
        $this->assertTrue($statusChange->changedBy->is($actor));
        $this->assertTrue($actor->incidentStatusChanges->contains($statusChange));
        $this->assertSame(IncidentStatus::Open, $statusChange->from_status);
        $this->assertSame(IncidentStatus::InProgress, $statusChange->to_status);

        $technician->delete();

        $incident->refresh();

        $this->assertSame($technician->getKey(), $incident->assigned_technician_id);
        $this->assertTrue($incident->assignedTechnician->is($technician));
        $this->assertTrue($incident->assignedTechnician->trashed());

        $incident->delete();

        $this->assertDatabaseMissing(IncidentStatusChange::class, [
            'id' => $statusChange->getKey(),
        ]);
    }

    public function test_each_status_exposes_only_its_linear_successor(): void
    {
        $this->assertSame(IncidentStatus::InProgress, IncidentStatus::Open->next());
        $this->assertSame(IncidentStatus::Resolved, IncidentStatus::InProgress->next());
        $this->assertSame(IncidentStatus::Closed, IncidentStatus::Resolved->next());
        $this->assertNull(IncidentStatus::Closed->next());
    }

    public function test_an_admin_can_assign_and_reassign_only_technicians(): void
    {
        $admin = User::factory()->admin()->create();
        $firstTechnician = User::factory()->technician()->create();
        $secondTechnician = User::factory()->technician()->create();
        $requester = User::factory()->create();
        $incident = Incident::factory()->create();
        $workflow = app(IncidentWorkflow::class);

        $workflow->assignTechnician($admin, $incident, $firstTechnician);
        $this->assertTrue($incident->assignedTechnician->is($firstTechnician));

        $workflow->assignTechnician($admin, $incident, $secondTechnician);
        $this->assertTrue($incident->assignedTechnician->is($secondTechnician));

        try {
            $workflow->assignTechnician($admin, $incident, $requester);
            $this->fail('A requester cannot be assigned as the technician.');
        } catch (ValidationException $exception) {
            $this->assertArrayHasKey('technician_id', $exception->errors());
        }

        $this->assertTrue($incident->refresh()->assignedTechnician->is($secondTechnician));
    }

    public function test_an_incident_follows_the_complete_status_flow_and_records_history(): void
    {
        $admin = User::factory()->admin()->create();
        $incident = Incident::factory()->create();
        $workflow = app(IncidentWorkflow::class);

        foreach ([IncidentStatus::InProgress, IncidentStatus::Resolved, IncidentStatus::Closed] as $status) {
            $change = $workflow->transition($admin, $incident, $status);

            $this->assertSame($status, $incident->status);
            $this->assertSame($status, $change->to_status);
            $this->assertTrue($change->changedBy->is($admin));
        }

        $this->assertSame([
            IncidentStatus::Open,
            IncidentStatus::InProgress,
            IncidentStatus::Resolved,
        ], $incident->statusChanges->pluck('from_status')->all());
        $this->assertSame([
            IncidentStatus::InProgress,
            IncidentStatus::Resolved,
            IncidentStatus::Closed,
        ], $incident->statusChanges->pluck('to_status')->all());
    }

    public function test_skips_and_backtracking_are_rejected_without_history(): void
    {
        $admin = User::factory()->admin()->create();
        $incident = Incident::factory()->create();
        $workflow = app(IncidentWorkflow::class);

        foreach ([IncidentStatus::Closed, IncidentStatus::Resolved] as $invalidStatus) {
            try {
                $workflow->transition($admin, $incident, $invalidStatus);
                $this->fail('An invalid transition was accepted.');
            } catch (ValidationException $exception) {
                $this->assertArrayHasKey('status', $exception->errors());
            }
        }

        $this->assertSame(IncidentStatus::Open, $incident->refresh()->status);
        $this->assertDatabaseCount(IncidentStatusChange::class, 0);

        $workflow->transition($admin, $incident, IncidentStatus::InProgress);

        try {
            $workflow->transition($admin, $incident, IncidentStatus::Open);
            $this->fail('A status backtrack was accepted.');
        } catch (ValidationException $exception) {
            $this->assertArrayHasKey('status', $exception->errors());
        }

        $this->assertSame(IncidentStatus::InProgress, $incident->refresh()->status);
        $this->assertDatabaseCount(IncidentStatusChange::class, 1);
    }

    public function test_tracking_permissions_are_enforced_by_policy_and_service(): void
    {
        $admin = User::factory()->admin()->create();
        $assignedTechnician = User::factory()->technician()->create();
        $otherTechnician = User::factory()->technician()->create();
        $requester = User::factory()->create();
        $incident = Incident::factory()
            ->forRequester($requester)
            ->assignedTo($assignedTechnician)
            ->create();

        $this->assertTrue(Gate::forUser($admin)->allows('assignTechnician', $incident));
        $this->assertFalse(Gate::forUser($assignedTechnician)->allows('assignTechnician', $incident));
        $this->assertFalse(Gate::forUser($requester)->allows('assignTechnician', $incident));
        $this->assertTrue(Gate::forUser($admin)->allows('changeStatus', $incident));
        $this->assertTrue(Gate::forUser($assignedTechnician)->allows('changeStatus', $incident));
        $this->assertFalse(Gate::forUser($otherTechnician)->allows('changeStatus', $incident));
        $this->assertFalse(Gate::forUser($requester)->allows('changeStatus', $incident));
        $this->assertFalse(Gate::forUser($admin)->allows('update', $incident));

        $workflow = app(IncidentWorkflow::class);

        foreach ([$assignedTechnician, $requester] as $unauthorizedUser) {
            try {
                $workflow->assignTechnician($unauthorizedUser, $incident, $otherTechnician);
                $this->fail('An unauthorized user assigned a technician.');
            } catch (AuthorizationException) {
                $this->assertTrue(true);
            }
        }

        foreach ([$otherTechnician, $requester] as $unauthorizedUser) {
            try {
                $workflow->transition($unauthorizedUser, $incident, IncidentStatus::InProgress);
                $this->fail('An unauthorized user changed the status.');
            } catch (AuthorizationException) {
                $this->assertTrue(true);
            }
        }

        $this->assertSame(IncidentStatus::Open, $incident->refresh()->status);
        $this->assertDatabaseCount(IncidentStatusChange::class, 0);

        $workflow->transition($assignedTechnician, $incident, IncidentStatus::InProgress);

        $this->assertSame(IncidentStatus::InProgress, $incident->status);
    }
}
