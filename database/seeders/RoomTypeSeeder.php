<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\RoomType;

class RoomTypeSeeder extends Seeder
{
    public function run()
    {
        $types = [
            ['name' => 'Single', 'capacity' => 1, 'base_price' => 50, 'amenities' => 'WiFi,TV,AC'],
            ['name' => 'Double', 'capacity' => 2, 'base_price' => 80, 'amenities' => 'WiFi,TV,AC'],
            ['name' => 'Suite', 'capacity' => 4, 'base_price' => 150, 'amenities' => 'WiFi,TV,AC,Mini-bar'],
        ];

        foreach ($types as $t) {
            RoomType::updateOrCreate(['name' => $t['name']], $t);
        }
    }
}
