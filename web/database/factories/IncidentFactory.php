<?php

namespace Database\Factories;

use App\Enums\IncidentPriority;
use App\Enums\IncidentStatus;
use App\Models\Asset;
use App\Models\Incident;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Incident>
 */
class IncidentFactory extends Factory
{
    /**
     * @var list<string>
     */
    private const TITLES = [
        'Fuga de agua en el baño de la segunda planta',
        'No funciona el aire acondicionado de la sala de reuniones',
        'Luz fundida en el pasillo principal',
        'La puerta del almacén no cierra bien',
        'Ruido extraño en la cinta transportadora',
        'Enchufe sin corriente en el puesto 14',
    ];

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => fake()->randomElement(self::TITLES),
            'description' => 'Se detectó a primera hora. Por favor, revisadlo cuanto antes.',
            'priority' => fake()->randomElement(IncidentPriority::cases()),
            'status' => IncidentStatus::Open,
            'requester_id' => User::factory(),
            'asset_id' => Asset::factory(),
        ];
    }

    public function status(IncidentStatus $status): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => $status,
        ]);
    }

    public function assignedTo(User $technician): static
    {
        return $this->state(fn (array $attributes) => [
            'technician_id' => $technician->id,
        ]);
    }

    /**
     * An incident created before assets existed.
     */
    public function withoutAsset(): static
    {
        return $this->state(fn (array $attributes) => [
            'asset_id' => null,
        ]);
    }

    public function priority(IncidentPriority $priority): static
    {
        return $this->state(fn (array $attributes) => [
            'priority' => $priority,
        ]);
    }
}
