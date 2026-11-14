<?php

namespace Tests\Feature;

use App\Models\Incident;
use App\Models\IncidentComment;
use App\Models\IncidentStatusChange;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class UserManagementPersistenceTest extends TestCase
{
    use RefreshDatabase;

    public function test_a_soft_deleted_user_is_excluded_from_active_queries(): void
    {
        $user = User::factory()->create([
            'email' => 'deleted@example.com',
            'password' => 'secret-password',
        ]);

        $user->delete();

        $this->assertSoftDeleted($user);
        $this->assertNull(User::query()->find($user->getKey()));
        $this->assertTrue(User::withTrashed()->findOrFail($user->getKey())->is($user));
        $this->assertFalse(Auth::attempt([
            'email' => 'deleted@example.com',
            'password' => 'secret-password',
        ]));
    }

    public function test_incident_history_keeps_soft_deleted_user_names(): void
    {
        $requester = User::factory()->create(['name' => 'Solicitante eliminado']);
        $technician = User::factory()->technician()->create(['name' => 'Técnico eliminado']);
        $statusAuthor = User::factory()->admin()->create(['name' => 'Autor de estado eliminado']);
        $commentAuthor = User::factory()->create(['name' => 'Autor de comentario eliminado']);
        $incident = Incident::factory()
            ->forRequester($requester)
            ->assignedTo($technician)
            ->create();
        $statusChange = IncidentStatusChange::factory()->create([
            'incident_id' => $incident->getKey(),
            'changed_by_id' => $statusAuthor->getKey(),
        ]);
        $comment = IncidentComment::factory()
            ->forIncident($incident)
            ->by($commentAuthor)
            ->create();

        collect([$requester, $technician, $statusAuthor, $commentAuthor])
            ->each(fn (User $user) => $user->delete());

        $incident = $incident->fresh();

        $this->assertSame('Solicitante eliminado', $incident->requester->name);
        $this->assertSame('Técnico eliminado', $incident->assignedTechnician->name);
        $this->assertSame('Autor de estado eliminado', $statusChange->fresh()->changedBy->name);
        $this->assertSame('Autor de comentario eliminado', $comment->fresh()->author->name);
    }
}
