<?php

namespace Tests\Feature;

use App\Enums\IncidentStatus;
use App\Filament\Widgets\IncidentStatusOverview;
use App\Models\Incident;
use App\Models\User;
use Filament\Facades\Filament;
use Filament\Pages\Dashboard;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class IncidentStatusOverviewTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Filament::setCurrentPanel(Filament::getPanel('admin'));
    }

    public function test_it_always_shows_the_open_statuses_in_order_with_zero_defaults(): void
    {
        $admin = User::factory()->admin()->create();
        Incident::factory()->count(4)->status(IncidentStatus::Closed)->create();
        $this->actingAs($admin);

        Livewire::test(IncidentStatusOverview::class)
            ->assertSeeInOrder([
                'Incidencias por estado',
                'Abierta',
                '0',
                'En curso',
                '0',
                'Resuelta',
                '0',
            ])
            ->assertDontSee('Cerrada')
            ->assertDontSeeHtml('<a')
            ->assertDontSeeHtml('wire:poll');
    }

    public function test_an_administrator_sees_exact_counts_for_every_open_status(): void
    {
        $admin = User::factory()->admin()->create();
        $this->createIncidents(IncidentStatus::Open, 2);
        $this->createIncidents(IncidentStatus::InProgress, 3);
        $this->createIncidents(IncidentStatus::Resolved, 4);
        $this->createIncidents(IncidentStatus::Closed, 5);
        $this->actingAs($admin);

        $this->assertWidgetCounts(2, 3, 4);
    }

    public function test_a_technician_sees_exact_counts_for_every_open_status(): void
    {
        $technician = User::factory()->technician()->create();
        $this->createIncidents(IncidentStatus::Open, 1);
        $this->createIncidents(IncidentStatus::InProgress, 2);
        $this->createIncidents(IncidentStatus::Resolved, 3);
        $this->actingAs($technician);

        $this->assertWidgetCounts(1, 2, 3);
    }

    public function test_a_requester_only_sees_counts_for_their_own_incidents(): void
    {
        $requester = User::factory()->create();
        $otherRequester = User::factory()->create();
        $this->createIncidents(IncidentStatus::Open, 1, $requester);
        $this->createIncidents(IncidentStatus::InProgress, 2, $requester);
        $this->createIncidents(IncidentStatus::Resolved, 3, $requester);
        $this->createIncidents(IncidentStatus::Open, 4, $otherRequester);
        $this->createIncidents(IncidentStatus::InProgress, 5, $otherRequester);
        $this->createIncidents(IncidentStatus::Resolved, 6, $otherRequester);
        $this->actingAs($requester);

        $this->assertWidgetCounts(1, 2, 3);
    }

    public function test_the_dashboard_discovers_the_widget_for_every_role(): void
    {
        $users = [
            User::factory()->admin()->create(),
            User::factory()->technician()->create(),
            User::factory()->create(),
        ];

        $this->assertContains(IncidentStatusOverview::class, Filament::getWidgets());

        foreach ($users as $user) {
            $this->actingAs($user);

            Livewire::test(Dashboard::class)
                ->assertSeeLivewire(IncidentStatusOverview::class);
        }
    }

    public function test_the_widget_is_hidden_without_an_authenticated_user(): void
    {
        $this->assertFalse(IncidentStatusOverview::canView());
    }

    public function test_remounting_the_widget_recalculates_the_current_counts(): void
    {
        $admin = User::factory()->admin()->create();
        $this->actingAs($admin);

        $this->assertWidgetCounts(0, 0, 0);

        $this->createIncidents(IncidentStatus::Open, 2);

        $this->assertWidgetCounts(2, 0, 0);
    }

    private function assertWidgetCounts(int $open, int $inProgress, int $resolved): void
    {
        Livewire::test(IncidentStatusOverview::class)
            ->assertSeeInOrder([
                'Abierta',
                (string) $open,
                'En curso',
                (string) $inProgress,
                'Resuelta',
                (string) $resolved,
            ]);
    }

    private function createIncidents(IncidentStatus $status, int $count, ?User $requester = null): void
    {
        $factory = Incident::factory()
            ->count($count)
            ->status($status);

        if ($requester instanceof User) {
            $factory = $factory->forRequester($requester);
        }

        $factory->create();
    }
}
