<?php

namespace Tests\Feature\Admin\Abstractions;

use App\Models\User;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminTestCase extends TestCase
{
    use RefreshDatabase;

    private string $main_route = '/admin/users';

    public User $admin;

    public function setUp(): void
    {
        parent::setUp();

//        parent::withHeader('Accept', 'application/json');

        $this->seed(RolesAndPermissionsSeeder::class);

        $this->CreateAdmin();

        $this->actingAs($this->admin);
    }

    private function CreateAdmin(): void
    {
        $this->admin =
            User::factory()
                ->admin()
                ->create();
    }
}
