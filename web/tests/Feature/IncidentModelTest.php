<?php

namespace Tests\Feature;

use App\Enums\IncidentPriority;
use App\Enums\IncidentStatus;
use App\Models\Incident;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class IncidentModelTest extends TestCase
{
    use RefreshDatabase;

    public function test_an_incident_persists_its_data_and_relationships(): void
    {
        $requester = User::factory()->create();

        $incident = Incident::factory()
            ->forRequester($requester)
            ->status(IncidentStatus::InProgress)
            ->priority(IncidentPriority::Critical)
            ->create([
                'title' => 'Fuga de agua en el pasillo',
                'description' => 'La tubería pierde agua junto a la entrada.',
            ]);

        $this->assertSame('Fuga de agua en el pasillo', $incident->title);
        $this->assertSame('La tubería pierde agua junto a la entrada.', $incident->description);
        $this->assertSame(IncidentStatus::InProgress, $incident->status);
        $this->assertSame(IncidentPriority::Critical, $incident->priority);
        $this->assertTrue($incident->requester->is($requester));
        $this->assertTrue($requester->requestedIncidents->contains($incident));
    }

    public function test_the_factory_supports_every_status_and_priority(): void
    {
        foreach (IncidentStatus::cases() as $status) {
            $incident = Incident::factory()->status($status)->create();

            $this->assertSame($status, $incident->status);
        }

        foreach (IncidentPriority::cases() as $priority) {
            $incident = Incident::factory()->priority($priority)->create();

            $this->assertSame($priority, $incident->priority);
        }
    }
}
