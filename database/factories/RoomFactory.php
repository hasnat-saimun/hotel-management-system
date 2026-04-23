<?php

namespace Database\Factories;

use App\Models\Floor;
use App\Models\RoomType;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Room>
 */
class RoomFactory extends Factory
{
    public function definition(): array
    {
        return [
            'room_number' => (string) fake()->unique()->numberBetween(100, 9999),
            'room_type_id' => RoomType::factory(),
            'floor_id' => Floor::factory(),
            'status' => 'available',
            'notes' => null,
            'is_active' => true,
            'avatar' => null,
        ];
    }
}
