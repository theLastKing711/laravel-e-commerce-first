<?php

namespace Tests\Feature\User\Abstractions;

use App\Models\User;
use Database\Seeders\CategorySeeder;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserTestCase extends TestCase
{
    use RefreshDatabase;

    private string $main_route = '/admin/users';

    public User $user;

    protected function setUp(): void
    {
        parent::setUp();

        //        parent::withHeader('Accept', 'application/json');

        $this->seed(RolesAndPermissionsSeeder::class);
        // $this->seed(CategorySeeder::class);

        $this->createUser();

        $this->actingAs($this->user);
    }

    private function createUser(): void
    {
        $this->user =
            User::factory()
                ->user()
                ->create();
    }
}
