<?php

namespace Database\Seeders;

use App\Enums\IncidentPriority;
use App\Enums\IncidentStatus;
use App\Models\Asset;
use App\Models\User;
use Illuminate\Database\Seeder;

class IncidentSeeder extends Seeder
{
    /**
     * Demo incidents. Each one starts open and, when it has a technician,
     * walks through the given statuses so it gets a status history.
     *
     * @var list<array{requester: string, asset: string, title: string, description: string, priority: IncidentPriority, technician?: string, statuses?: list<IncidentStatus>}>
     */
    public const INCIDENTS = [
        [
            'requester' => 'solicitante1@infraticket.test',
            'asset' => 'Aseos planta 2',
            'title' => 'Fuga de agua en el baño de la segunda planta',
            'description' => 'Gotea el grifo del lavabo de la derecha y hay agua en el suelo.',
            'priority' => IncidentPriority::High,
            'technician' => 'tecnico1@infraticket.test',
            'statuses' => [IncidentStatus::InProgress],
        ],
        [
            'requester' => 'solicitante1@infraticket.test',
            'asset' => 'Alumbrado pasillo principal',
            'title' => 'Luz fundida en el pasillo principal',
            'description' => 'El fluorescente junto a la máquina de café no enciende.',
            'priority' => IncidentPriority::Low,
        ],
        [
            'requester' => 'solicitante2@infraticket.test',
            'asset' => 'Climatizador sala de reuniones',
            'title' => 'No funciona el aire acondicionado de la sala de reuniones',
            'description' => 'El equipo arranca pero no enfría.',
            'priority' => IncidentPriority::Medium,
            'technician' => 'tecnico2@infraticket.test',
            'statuses' => [IncidentStatus::InProgress, IncidentStatus::Resolved],
        ],
        [
            'requester' => 'solicitante2@infraticket.test',
            'asset' => 'Cuadro eléctrico de producción',
            'title' => 'Cuadro eléctrico salta continuamente',
            'description' => 'El diferencial de la zona de producción salta varias veces al día.',
            'priority' => IncidentPriority::Critical,
        ],
        [
            'requester' => 'solicitante1@infraticket.test',
            'asset' => 'Puerta de muelle 1',
            'title' => 'La puerta del almacén no cierra bien',
            'description' => 'Hay que empujarla con fuerza para que encaje el pestillo.',
            'priority' => IncidentPriority::Medium,
            'technician' => 'tecnico1@infraticket.test',
            'statuses' => [IncidentStatus::InProgress, IncidentStatus::Resolved, IncidentStatus::Closed],
        ],
    ];

    /**
     * Seed demo incidents. Safe to run more than once.
     */
    public function run(): void
    {
        foreach (self::INCIDENTS as $data) {
            $requester = User::where('email', $data['requester'])->first();

            if ($requester === null || $requester->incidents()->where('title', $data['title'])->exists()) {
                continue;
            }

            $incident = $requester->incidents()->create([
                'asset_id' => Asset::where('name', $data['asset'])->value('id'),
                'title' => $data['title'],
                'description' => $data['description'],
                'priority' => $data['priority'],
            ]);

            $technician = isset($data['technician']) ? User::where('email', $data['technician'])->first() : null;

            if ($technician === null) {
                continue;
            }

            $incident->assignTo($technician);

            $incident->comments()->create([
                'user_id' => $technician->id,
                'body' => '¿Desde cuándo ocurre? ¿Afecta a todo el día o solo a ciertas horas?',
            ]);
            $incident->comments()->create([
                'user_id' => $requester->id,
                'body' => 'Desde ayer por la mañana, y pasa todo el día.',
            ]);

            foreach ($data['statuses'] ?? [] as $status) {
                $incident->changeStatus($status, $technician);
            }
        }
    }
}
