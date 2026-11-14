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
            'author_id' => User::factory(),
            'content' => fake()->paragraph(),
        ];
    }

    public function forIncident(Incident $incident): static
    {
        return $this->state(fn (array $attributes) => [
            'incident_id' => $incident->getKey(),
        ]);
    }

    public function by(User $author): static
    {
        return $this->state(fn (array $attributes) => [
            'author_id' => $author->getKey(),
        ]);
    }
}
