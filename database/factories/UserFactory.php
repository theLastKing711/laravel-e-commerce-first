<?php

namespace Database\Factories;

use App\Enum\Auth\RolesEnum;
use App\Enum\Gender;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends Factory<User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => static::$password ??= Hash::make('password'),
            'remember_token' => Str::random(10),
        ];
    }

    public function admin(): static
    {
        return $this->afterCreating(function (User $user) {
            $user->assignRole(RolesEnum::ADMIN);
        });
    }

    public function adminWithAdminCredentials(): static
    {
        return $this->state(fn (array $attributes) => [
            'name' => 'admin',
            'email' => 'admin@admin.com',
            'password' => Hash::make('admin'),
        ])->afterCreating(function (User $user) {
            $user->assignRole(RolesEnum::ADMIN);
        });
    }

    public function storeUser(): static
    {
        return $this->state(fn (array $attributes) => [
            'name' => 'store',
            'password' => 'store',
        ])
            ->afterCreating(function (User $user) {
                $user->assignRole(RolesEnum::STORE);
            });
    }

    public function storeUserWithStoreCredentials(): static
    {

        return $this->state(fn (array $attributes) => [
            'name' => $this->faker->name(),
            'password' => static::$password ??= Hash::make('password'),
        ])
            ->afterCreating(function (User $user) {
                $user->assignRole(RolesEnum::STORE);
            });
    }

    public function user(): static
    {
        return $this->state(fn (array $attributes) => [
            'email' => null,
            'email_verified_at' => null,
            'password' => null,
            'gender' => $this->faker->randomElement(Gender::cases()),
            'dial_code' => '963',
            'number' => '096'.(string) fake()->randomNumber(7, true),
            'code' => '123456',
        ])
            ->afterCreating(function (User $user) {
                $user->assignRole(RolesEnum::USER);
            });
    }

    public function userWithUserCredentials(): static
    {
        return $this->state(fn (array $attributes) => [
            'gender' => Gender::Male,
            'dial_code' => '963',
            'number' => '0968259851',
            'code' => '123456',
        ])->afterCreating(function (User $user) {
            $user->assignRole(RolesEnum::USER);
        });
    }

    public function driver(): static
    {
        return $this->state(fn (array $attributes) => [
            'name' => fake()->name(),
            'username' => fake()->unique()->username(),
            'password' => fake()->password(),
            'number' => '096'.(string) fake()->randomNumber(7, true),
        ])
            ->afterCreating(function (User $driver) {
                $driver->assignRole(RolesEnum::DRIVER);
            });
    }

    public function driverWithDriverCredentials(): static
    {
        return $this->state(fn (array $attributes) => [
            'name' => 'driver',
            'username' => 'driver',
            'password' => 'driver',
            'number' => '0968259852',
        ])->afterCreating(function (User $user) {
            $user->assignRole(RolesEnum::DRIVER);
        });
    }
}
