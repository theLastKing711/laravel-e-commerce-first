<?php

namespace Database\Seeders;

use App\Enum\Day;
use App\Models\WorkDays;
use Illuminate\Database\Seeder;

class WorkDaysSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach (Day::cases() as $day) {

            $is_vacation = $day === Day::Friday || $day === Day::Saturday;

            WorkDays::create(
                [
                    'number' => $day->value,
                    'is_vacation' => $is_vacation,
                    'from' => '9:00',
                    'to' => '17:00',
                ]
            );
        }
    }
}
