<?php

namespace Database\Factories;

use App\Enum\Auth\RolesEnum;
use App\Models\Group;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Group>
 */
class GroupFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
        ];
    }


    //add records to group_user table after creating the group with role User
    public function appendGroupUser(): static
    {

        return $this->afterCreating(function (Group $group) {
            $userRole = RolesEnum::USER->value;

            $users = User::role($userRole)->inRandomOrder()->take(random_int(1, 3))->pluck('id');

            $group->users()->attach($users);
        });
    }
}
