<?php

namespace App\Exceptions;

use App\Enums\IncidentStatus;
use RuntimeException;

/**
 * Thrown when an incident cannot move to the requested status.
 * The message is shown to the user, so it is written in Spanish.
 */
class InvalidIncidentTransition extends RuntimeException
{
    public static function notAllowed(IncidentStatus $from, IncidentStatus $to): self
    {
        return new self("No se puede pasar una incidencia de «{$from->getLabel()}» a «{$to->getLabel()}».");
    }

    public static function missingTechnician(): self
    {
        return new self('Asigna un técnico antes de poner la incidencia en curso.');
    }
}
