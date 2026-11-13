<?php

namespace Tests\Feature;

use App\Models\Incident;
use App\Models\User;
use App\Policies\IncidentPolicy;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Gate;
use Tests\TestCase;

class IncidentAuthorizationTest extends TestCase
{
    use RefreshDatabase;

    public function test_requesters_only_see_and_open_their_own_incidents(): void
    {
        $requester = User::factory()->create();
        $otherRequester = User::factory()->create();
        $ownIncident = Incident::factory()->forRequester($requester)->create();
        $otherIncident = Incident::factory()->forRequester($otherRequester)->create();

        $visibleIncidents = app(IncidentPolicy::class)
            ->scopeQuery($requester, Incident::query())
            ->get();

        $this->assertTrue(Gate::forUser($requester)->allows('viewAny', Incident::class));
        $this->assertTrue(Gate::forUser($requester)->allows('view', $ownIncident));
        $this->assertFalse(Gate::forUser($requester)->allows('view', $otherIncident));
        $this->assertCount(1, $visibleIncidents);
        $this->assertTrue($visibleIncidents->contains($ownIncident));
    }

    public function test_technicians_and_administrators_can_see_every_incident(): void
    {
        $incidents = Incident::factory()->count(3)->create();
        $users = [
            User::factory()->technician()->create(),
            User::factory()->admin()->create(),
        ];

        foreach ($users as $user) {
            $visibleIncidents = app(IncidentPolicy::class)
                ->scopeQuery($user, Incident::query())
                ->get();

            $this->assertCount(3, $visibleIncidents);
            $this->assertTrue(Gate::forUser($user)->allows('view', $incidents->first()));
        }
    }

    public function test_only_requesters_can_create_incidents(): void
    {
        $requester = User::factory()->create();
        $technician = User::factory()->technician()->create();
        $admin = User::factory()->admin()->create();

        $this->assertTrue(Gate::forUser($requester)->allows('create', Incident::class));
        $this->assertFalse(Gate::forUser($technician)->allows('create', Incident::class));
        $this->assertFalse(Gate::forUser($admin)->allows('create', Incident::class));
    }

    public function test_incidents_cannot_be_updated_or_deleted(): void
    {
        $incident = Incident::factory()->create();
        $users = [
            $incident->requester,
            User::factory()->technician()->create(),
            User::factory()->admin()->create(),
        ];

        foreach ($users as $user) {
            $this->assertFalse(Gate::forUser($user)->allows('update', $incident));
            $this->assertFalse(Gate::forUser($user)->allows('delete', $incident));
            $this->assertFalse(Gate::forUser($user)->allows('restore', $incident));
            $this->assertFalse(Gate::forUser($user)->allows('forceDelete', $incident));
        }

    }
}
