<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ExtraService;

class ExtraServiceSeeder extends Seeder
{
    public function run()
    {
        $services = [
            ['name' => 'Late checkout', 'price' => 20, 'description' => 'Extend checkout until 3pm'],
            ['name' => 'Breakfast', 'price' => 10, 'description' => 'Continental breakfast'],
            ['name' => 'Airport pickup', 'price' => 30, 'description' => 'Pickup from airport'],
        ];

        foreach ($services as $s) {
            ExtraService::updateOrCreate(['name' => $s['name']], $s);
        }
    }
}
