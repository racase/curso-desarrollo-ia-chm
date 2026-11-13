<?php

namespace Tests\Feature;

use App\Enums\IncidentStatus;
use App\Filament\Resources\Incidents\IncidentResource;
use App\Filament\Resources\Incidents\Pages\ViewIncident;
use App\Models\Incident;
use App\Models\IncidentComment;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Livewire\Livewire;
use Tests\TestCase;

class IncidentCommentTest extends TestCase
{
    use RefreshDatabase;

    public function test_a_technician_comments_on_an_incident(): void
    {
        $this->travelTo(now()->setDateTime(2026, 11, 13, 10, 30));
        $tomas = User::factory()->technician()->create(['name' => 'Tomás Técnico']);
        $incident = Incident::factory()->create();

        $this->actingAs($tomas);

        Livewire::test(ViewIncident::class, ['record' => $incident->getRouteKey()])
            ->callAction('comment', data: ['body' => '¿Desde cuándo ocurre?'])
            ->assertHasNoActionErrors();

        $comment = $incident->comments()->sole();
        $this->assertSame('¿Desde cuándo ocurre?', $comment->body);
        $this->assertTrue($comment->author->is($tomas));

        $this->get(IncidentResource::getUrl('view', ['record' => $incident]))
            ->assertOk()
            ->assertSeeInOrder(['Comentarios', 'Tomás Técnico', '13/11/2026 10:30', '¿Desde cuándo ocurre?']);
    }

    public function test_the_requester_reads_and_answers_a_comment(): void
    {
        $sara = User::factory()->create();
        $incident = Incident::factory()->for($sara, 'requester')->create();
        IncidentComment::factory()->for($incident)->create(['body' => 'Paso esta tarde a revisarlo.']);

        $this->actingAs($sara)
            ->get(IncidentResource::getUrl('view', ['record' => $incident]))
            ->assertOk()
            ->assertSee('Paso esta tarde a revisarlo.');

        Livewire::test(ViewIncident::class, ['record' => $incident->getRouteKey()])
            ->callAction('comment', data: ['body' => 'Perfecto, gracias.'])
            ->assertHasNoActionErrors();

        $this->assertSame(2, $incident->comments()->count());
    }

    public function test_comments_are_shown_oldest_first(): void
    {
        $sara = User::factory()->create();
        $incident = Incident::factory()->for($sara, 'requester')->create();

        $this->travelTo(now()->subHour());
        IncidentComment::factory()->for($incident)->create(['body' => 'Primer comentario']);
        $this->travelBack();
        IncidentComment::factory()->for($incident)->create(['body' => 'Segundo comentario']);

        $this->actingAs($sara)
            ->get(IncidentResource::getUrl('view', ['record' => $incident]))
            ->assertSeeInOrder(['Primer comentario', 'Segundo comentario']);
    }

    public function test_an_empty_comment_is_rejected_in_spanish(): void
    {
        $incident = Incident::factory()->create();
        $this->actingAs(User::factory()->technician()->create());

        $component = Livewire::test(ViewIncident::class, ['record' => $incident->getRouteKey()])
            ->callAction('comment', data: ['body' => ''])
            ->assertHasActionErrors(['body' => 'required']);

        $this->assertContains('El campo comentario es obligatorio.', $component->errors()->all());
        $this->assertSame(0, IncidentComment::count());
    }

    public function test_a_too_long_comment_is_rejected_in_spanish(): void
    {
        $incident = Incident::factory()->create();
        $this->actingAs(User::factory()->technician()->create());

        $component = Livewire::test(ViewIncident::class, ['record' => $incident->getRouteKey()])
            ->callAction('comment', data: ['body' => Str::repeat('a', 2001)])
            ->assertHasActionErrors(['body' => 'max']);

        $this->assertContains('El campo comentario no puede tener más de 2000 caracteres.', $component->errors()->all());
        $this->assertSame(0, IncidentComment::count());
    }

    public function test_a_requester_cannot_comment_on_someone_elses_incident(): void
    {
        $sergio = User::factory()->create();
        $incident = Incident::factory()->create();

        $this->assertFalse($sergio->can('comment', $incident));

        $this->actingAs($sergio)
            ->get(IncidentResource::getUrl('view', ['record' => $incident]))
            ->assertNotFound();
    }

    public function test_closed_incidents_keep_their_comments_but_take_no_new_ones(): void
    {
        $sara = User::factory()->create();
        $incident = Incident::factory()->for($sara, 'requester')->status(IncidentStatus::Closed)->create();
        IncidentComment::factory()->for($incident)->create(['body' => 'Ya está arreglado.']);

        $this->assertFalse(User::factory()->admin()->create()->can('comment', $incident));

        $this->actingAs($sara)
            ->get(IncidentResource::getUrl('view', ['record' => $incident]))
            ->assertSee('Ya está arreglado.');

        Livewire::test(ViewIncident::class, ['record' => $incident->getRouteKey()])
            ->assertActionHidden('comment');
    }

    public function test_everyone_who_sees_the_incident_can_comment(): void
    {
        $sara = User::factory()->create();
        $incident = Incident::factory()->for($sara, 'requester')->create();

        foreach ([$sara, User::factory()->technician()->create(), User::factory()->admin()->create()] as $user) {
            $this->assertTrue($user->can('comment', $incident));
        }
    }
}
