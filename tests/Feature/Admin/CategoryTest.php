<?php

namespace Tests\Feature\Admin;

use App\Models\Category;
use Database\Seeders\CategorySeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CategoryTest extends TestCase
{

    public string $route = "/admin/categories";

    use RefreshDatabase;
    /**
     * A basic feature test example.
     */
    public function admin_return_a_list_of_categories_with_200_response(): void
    {
        $this->seed(CategorySeeder::class);

        $response = $this->get($this->route);

        $response->assertJsonStructure();

        $response->assertJsonIsArray();

        $response->assertJsonCount(16);

        $response->assertStatus(200);
    }
}
