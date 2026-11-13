<?php

namespace Tests\Unit;

use App\Enums\IncidentStatus;
use PHPUnit\Framework\TestCase;

class IncidentStatusTransitionsTest extends TestCase
{
    public function test_the_transition_table(): void
    {
        $this->assertSame([IncidentStatus::InProgress], IncidentStatus::Open->allowedTransitions());
        $this->assertSame([IncidentStatus::Open, IncidentStatus::Resolved], IncidentStatus::InProgress->allowedTransitions());
        $this->assertSame([IncidentStatus::InProgress, IncidentStatus::Closed], IncidentStatus::Resolved->allowedTransitions());
        $this->assertSame([], IncidentStatus::Closed->allowedTransitions());
    }

    public function test_an_incident_cannot_be_closed_before_it_is_resolved(): void
    {
        $this->assertFalse(IncidentStatus::Open->canTransitionTo(IncidentStatus::Closed));
        $this->assertFalse(IncidentStatus::InProgress->canTransitionTo(IncidentStatus::Closed));
        $this->assertTrue(IncidentStatus::Resolved->canTransitionTo(IncidentStatus::Closed));
    }
}
