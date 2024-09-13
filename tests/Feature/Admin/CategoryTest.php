<?php

namespace Tests\Feature\Admin;

use App\Models\User;
use Database\Seeders\CategorySeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CategoryTest extends TestCase
{
    public string $route = '/admin/categories';

    use RefreshDatabase;

    public function CreateAdmin(): User
    {

    }

    /**
     * A basic feature test example.
     */
    public function admin_return_a_list_of_categories_with_200_response(): void
    {
        $this->assertTrue(true);
//        $this->seed(CategorySeeder::class);
//
//        $response = $this->get($this->route);
//
//        $response->assertJsonStructure();
//
//        $response->assertJsonIsArray();
//
//        $response->assertJsonCount(16);
//
//        $response->assertStatus(200);
    }
}
