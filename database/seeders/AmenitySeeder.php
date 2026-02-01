<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Amenity;

class AmenitySeeder extends Seeder
{
    public function run()
    {
        $list = [
            ['name' => 'WiFi', 'icon' => '<i class="ki-filled ki-wifi"></i>'],
            ['name' => 'AC', 'icon' => '<i class="ki-filled ki-snowflake"></i>'],
            ['name' => 'TV', 'icon' => '<i class="ki-filled ki-screen"></i>'],
            ['name' => 'Mini-bar', 'icon' => '<i class="ki-filled ki-cup"></i>'],
        ];

        foreach ($list as $a) {
            Amenity::updateOrCreate(['name' => $a['name']], $a);
        }
    }
}
