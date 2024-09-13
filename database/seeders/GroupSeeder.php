<?php

namespace Database\Seeders;

use App\Enum\Auth\RolesEnum;
use App\Models\Group;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class GroupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->createGroupsWithUserGroup(4);
    }

    public function createGroupsWithUserGroup(int $count): void
    {
        Group::factory()
            ->count($count)
            ->appendGroupUser()
            ->create();

    }

}
