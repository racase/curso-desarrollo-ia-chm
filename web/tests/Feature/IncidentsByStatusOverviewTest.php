<?php

namespace Tests\Feature;

use App\Enums\IncidentStatus;
use App\Filament\Resources\Incidents\IncidentResource;
use App\Filament\Resources\Incidents\Pages\ListIncidents;
use App\Filament\Widgets\IncidentsByStatusOverview;
use App\Models\Incident;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use ReflectionMethod;
use Tests\TestCase;

class IncidentsByStatusOverviewTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @return array<string, int>
     */
    private function countsSeenBy(User $user): array
    {
        $this->actingAs($user);

        $widget = new IncidentsByStatusOverview;
        $stats = (new ReflectionMethod($widget, 'getStats'))->invoke($widget);

        return collect($stats)
            ->mapWithKeys(fn (Stat $stat) => [(string) $stat->getLabel() => $stat->getValue()])
            ->all();
    }

    private function seedScenario(User $sara): void
    {
        Incident::factory()->for($sara, 'requester')->status(IncidentStatus::Open)->create();
        Incident::factory()->count(2)->status(IncidentStatus::Open)->create();
        Incident::factory()->count(2)->status(IncidentStatus::InProgress)->create();
        Incident::factory()->status(IncidentStatus::Resolved)->create();
    }

    public function test_a_technician_counts_every_incident(): void
    {
        $this->seedScenario(User::factory()->create());

        $this->assertSame(
            ['Abierta' => 3, 'En curso' => 2, 'Resuelta' => 1, 'Cerrada' => 0],
            $this->countsSeenBy(User::factory()->technician()->create()),
        );
    }

    public function test_a_requester_only_counts_their_own_incidents(): void
    {
        $sara = User::factory()->create();
        $this->seedScenario($sara);

        $this->assertSame(
            ['Abierta' => 1, 'En curso' => 0, 'Resuelta' => 0, 'Cerrada' => 0],
            $this->countsSeenBy($sara),
        );
    }

    public function test_every_status_shows_zero_without_incidents(): void
    {
        foreach ([User::factory()->admin()->create(), User::factory()->technician()->create(), User::factory()->create()] as $user) {
            $this->assertSame(
                ['Abierta' => 0, 'En curso' => 0, 'Resuelta' => 0, 'Cerrada' => 0],
                $this->countsSeenBy($user),
            );
        }
    }

    public function test_the_dashboard_shows_the_summary(): void
    {
        $this->actingAs(User::factory()->technician()->create());
        Incident::factory()->status(IncidentStatus::InProgress)->create();

        Livewire::test(IncidentsByStatusOverview::class)
            ->assertOk()
            ->assertSeeInOrder(['Incidencias por estado', 'Abierta', 'En curso', 'Resuelta', 'Cerrada']);

        $this->get('/admin')
            ->assertOk()
            ->assertSeeLivewire(IncidentsByStatusOverview::class);
    }

    public function test_each_stat_links_to_the_filtered_list(): void
    {
        $this->actingAs(User::factory()->technician()->create());
        $open = Incident::factory()->status(IncidentStatus::Open)->create();
        $inProgress = Incident::factory()->status(IncidentStatus::InProgress)->create();

        $widget = new IncidentsByStatusOverview;
        $stats = (new ReflectionMethod($widget, 'getStats'))->invoke($widget);
        $url = collect($stats)->first(fn (Stat $stat) => $stat->getLabel() === 'En curso')->getUrl();

        $this->assertSame(
            IncidentResource::getUrl('index', ['filters' => ['status' => ['value' => 'in_progress']]]),
            $url,
        );

        Livewire::withQueryParams(['filters' => ['status' => ['value' => 'in_progress']]])
            ->test(ListIncidents::class)
            ->assertCanSeeTableRecords([$inProgress])
            ->assertCanNotSeeTableRecords([$open]);
    }
}
