<?php

namespace Database\Factories;

use App\Models\Asset;
use App\Models\Site;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Asset>
 */
class AssetFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'site_id' => Site::factory(),
            'name' => fake()->randomElement(['Compresor', 'Caldera', 'Carretilla', 'Cinta transportadora', 'Climatizador']).' '.fake()->unique()->numberBetween(1, 999),
        ];
    }
}
