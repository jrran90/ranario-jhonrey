<?php

namespace Database\Seeders;

use App\Models\StoreHour;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class StoreHourSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $hours = [
            ['day_of_week' => 1, 'open_time' => '08:00:00', 'close_time' => '16:00:00', 'is_open' => true],
            ['day_of_week' => 3, 'open_time' => '08:00:00', 'close_time' => '16:00:00', 'is_open' => true],
            ['day_of_week' => 5, 'open_time' => '08:00:00', 'close_time' => '16:00:00', 'is_open' => true],
        ];

        // Every other week Saturday hours
        if (date('W') % 2 == 0) {
            $hours[] = ['day_of_week' => 6, 'open_time' => '08:00:00', 'close_time' => '12:00:00', 'is_open' => true];
            $hours[] = ['day_of_week' => 6, 'open_time' => '12:45:00', 'close_time' => '16:00:00', 'is_open' => true];
        }

        foreach ($hours as $hour) {
            StoreHour::create($hour);
        }
    }
}
