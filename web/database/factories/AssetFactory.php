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
            'code' => fake()->unique()->bothify('ACT-####'),
            'name' => fake()->words(3, true),
            'site_id' => Site::factory(),
        ];
    }
}
