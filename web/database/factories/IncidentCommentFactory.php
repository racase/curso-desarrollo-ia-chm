<?php

namespace Database\Factories;

use App\Models\Incident;
use App\Models\IncidentComment;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<IncidentComment>
 */
class IncidentCommentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'incident_id' => Incident::factory(),
            'user_id' => User::factory()->technician(),
            'body' => fake()->randomElement([
                '¿Desde cuándo ocurre?',
                'Paso esta tarde a revisarlo.',
                'Gracias, ya funciona correctamente.',
            ]),
        ];
    }
}
