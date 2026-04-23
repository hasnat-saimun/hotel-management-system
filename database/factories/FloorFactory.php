<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Floor>
 */
class FloorFactory extends Factory
{
    public function definition(): array
    {
        $level = fake()->numberBetween(1, 20);

        return [
            'name' => 'Floor ' . $level,
            'level_number' => (string) $level,
        ];
    }
}
