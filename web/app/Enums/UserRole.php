<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum UserRole: string implements HasLabel
{
    case Admin = 'admin';
    case Technician = 'technician';
    case Requester = 'requester';

    public function getLabel(): string
    {
        return match ($this) {
            self::Admin => 'Administrador',
            self::Technician => 'Técnico',
            self::Requester => 'Solicitante',
        };
    }
}
