<?php

namespace Tests\Feature;

use App\Enums\IncidentStatus;
use App\Models\Incident;
use App\Models\IncidentComment;
use App\Models\User;
use App\Services\IncidentCommentService;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class IncidentCommentTest extends TestCase
{
    use RefreshDatabase;

    public function test_comments_preserve_data_relations_timestamps_and_stable_order(): void
    {
        $incident = Incident::factory()->create();
        $author = User::factory()->technician()->create();
        $publishedAt = now()->subMinute();
        $first = IncidentComment::factory()
            ->forIncident($incident)
            ->by($author)
            ->create([
                'content' => 'Primera aportación',
                'created_at' => $publishedAt,
                'updated_at' => $publishedAt,
            ]);
        $second = IncidentComment::factory()
            ->forIncident($incident)
            ->by($author)
            ->create([
                'content' => 'Segunda aportación',
                'created_at' => $publishedAt,
                'updated_at' => $publishedAt,
            ]);

        $this->assertTrue($first->incident->is($incident));
        $this->assertTrue($first->author->is($author));
        $this->assertTrue($author->incidentComments->contains($first));
        $this->assertNotNull($first->created_at);
        $this->assertSame(
            [$first->getKey(), $second->getKey()],
            $incident->comments->modelKeys(),
        );

        $incident->delete();

        $this->assertDatabaseCount(IncidentComment::class, 0);
    }

    public function test_an_author_cannot_be_deleted_while_their_comment_exists(): void
    {
        $author = User::factory()->create();
        IncidentComment::factory()->by($author)->create();

        $this->expectException(QueryException::class);

        $author->delete();
    }

    public function test_every_user_who_can_view_an_incident_can_publish_as_themselves(): void
    {
        $requester = User::factory()->create();
        $incident = Incident::factory()->forRequester($requester)->create();
        $users = [
            $requester,
            User::factory()->technician()->create(),
            User::factory()->admin()->create(),
        ];
        $service = app(IncidentCommentService::class);

        foreach ($users as $index => $user) {
            $comment = $service->publish($user, $incident, "  Aportación {$index}  ");

            $this->assertTrue($comment->author->is($user));
            $this->assertTrue($comment->incident->is($incident));
            $this->assertSame("Aportación {$index}", $comment->content);
        }

        $this->assertDatabaseCount(IncidentComment::class, 3);
    }

    public function test_a_requester_cannot_comment_on_another_requesters_incident(): void
    {
        $requester = User::factory()->create();
        $incident = Incident::factory()->create();

        try {
            app(IncidentCommentService::class)->publish($requester, $incident, 'No autorizado');
            $this->fail('A requester commented on another requester incident.');
        } catch (AuthorizationException) {
            $this->assertDatabaseCount(IncidentComment::class, 0);
        }
    }

    public function test_empty_comment_content_is_rejected(): void
    {
        $admin = User::factory()->admin()->create();
        $incident = Incident::factory()->create();

        try {
            app(IncidentCommentService::class)->publish($admin, $incident, '   ');
            $this->fail('An empty comment was accepted.');
        } catch (ValidationException $exception) {
            $this->assertArrayHasKey('content', $exception->errors());
        }

        $this->assertDatabaseCount(IncidentComment::class, 0);
    }

    public function test_authorized_users_can_comment_in_every_status_without_changing_it(): void
    {
        $admin = User::factory()->admin()->create();
        $service = app(IncidentCommentService::class);

        foreach (IncidentStatus::cases() as $status) {
            $incident = Incident::factory()->status($status)->create();

            $service->publish($admin, $incident, "Comentario en {$status->value}");

            $this->assertSame($status, $incident->refresh()->status);
            $this->assertCount(1, $incident->comments);
        }

        $this->assertDatabaseCount(IncidentComment::class, count(IncidentStatus::cases()));
    }
}
