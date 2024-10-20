<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RolesAndPermissionsSeeder::class,
            CategorySeeder::class,
            UserSeeder::class,
            OrderSeeder::class,
            GroupSeeder::class,
            WorkDaysSeeder::class,
            SettingSeeder::class,
        ]);
    }
}
