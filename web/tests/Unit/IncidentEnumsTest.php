<?php

namespace Tests\Unit;

use App\Enums\IncidentPriority;
use App\Enums\IncidentStatus;
use PHPUnit\Framework\TestCase;

class IncidentEnumsTest extends TestCase
{
    public function test_priorities_have_spanish_labels(): void
    {
        $this->assertSame(
            ['Baja', 'Media', 'Alta', 'Crítica'],
            array_map(fn (IncidentPriority $priority) => $priority->getLabel(), IncidentPriority::cases()),
        );
    }

    public function test_statuses_have_spanish_labels(): void
    {
        $this->assertSame(
            ['Abierta', 'En curso', 'Resuelta', 'Cerrada'],
            array_map(fn (IncidentStatus $status) => $status->getLabel(), IncidentStatus::cases()),
        );
    }
}
