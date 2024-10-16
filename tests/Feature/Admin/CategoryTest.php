<?php

namespace Tests\Feature\Admin;

use App\Data\Admin\Category\CreateCategoryData;
use App\Data\Admin\Category\UpdateCategoryData;
use App\Data\Shared\File\CreateFilePathData;
use App\Data\Shared\File\UpdateFileData;
use App\Models\Category;
use CloudinaryLabs\CloudinaryLaravel\CloudinaryEngine;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use Database\Seeders\CategorySeeder;
use Illuminate\Testing\Fluent\AssertableJson;
use Mockery\MockInterface;
use PHPUnit\Framework\Attributes\Test;
use Tests\Feature\Admin\Abstractions\AdminTestCase;
use Tests\Feature\Admin\Traits\MediaMockTrait;

class CategoryTest extends AdminTestCase
{
    use MediaMockTrait;

    private string $main_route = '/admin/categories';

    public function setUp(): void
    {
        parent::setUp();

        //create 10 parent + 10 child categories
        //20 in total
        CategorySeeder::generateTestCategories(10);
    }

    /**
     * A basic feature test example.
     */
    #[Test]
    public function index_return_a_list_of_categories_with_200_response(): void
    {

        $response = $this->get($this->main_route);

        $response->assertStatus(200);

        $categories_response_data = $response->json()['data'];

        $this->assertIsArray($categories_response_data);

        $first_db_parent_category = Category::query()
            ->with('parent')
            ->first();

        $response
            ->assertJson(
                fn (AssertableJson $json) => $json->has(
                    'data',
                    10,
                    fn (AssertableJson $json) => $json // runs one first item of json('data')
//                        ->tap(fn (AssertableJson $json) => Log::info($json))
                        ->where('id', $first_db_parent_category->id)
                        ->where('name', $first_db_parent_category->name)
                        ->where('parent_id', $first_db_parent_category->parent_id)
                        ->where('parent_name', $first_db_parent_category->parent?->name)
                        ->etc() // means don't need to specify all properties in json('data') here
                )
                    ->etc()// means don't need to specify all properties in json here
            );

    }

    #[Test]
    public function show_category_using_path_id_path_with_200_response(): void
    {

        $route_category = Category::first();

        $show_route = $this->main_route.'/'.$route_category->id;

        $response = $this->get($show_route);

        $response->assertStatus(200);

    }

    #[Test]
    public function store_create_a_new_user_with_201_response(): void
    {

        $createUserRequestData = new CreateCategoryData(
            name: 'new category name',
            parent_id: null,
            image_urls: CreateFilePathData::collect(
                collect(
                    [
                        new CreateFilePathData(url: 'test url', public_id: 'test public id'),
                        new CreateFilePathData(url: 'test url', public_id: 'test public id'),
                    ]
                )
            )
        );

        $this->mockMediaCreate();

        $response = $this->post(
            $this->main_route,
            $createUserRequestData->toArray()
        );

        $response->assertStatus(200);

        $created_category = Category::query()
            ->whereName($createUserRequestData->name)
            ->first();

        $this->assertNotNull($created_category);

    }

    #[Test]
    public function update_update_an_existing_user_with_201_response(): void
    {
        $route_category = Category::first();

        $show_route = $this->main_route.'/'.$route_category->id;

        $request_file_path_data = collect(
            [
                new UpdateFileData(uid: $route_category->id, url: 'test url 1'),
                new UpdateFileData(uid: $route_category->id, url: 'test url 2'),
            ]
        );

        $request_update_category_data = new UpdateCategoryData(
            name: 'updatedName',
            parent_id: null,
            image_urls: UpdateFileData::collect(
                $request_file_path_data
            )
        );

        $this->mockMediaUpdate();

        $response = $this->patch($show_route, $request_update_category_data->toArray());

        $response->assertStatus(200);

        $updated_category = Category::query()
            ->where('name', $request_update_category_data->name)
            ->where('parent_id', $request_update_category_data->parent_id)
            ->first();

        $this->assertNotNull($updated_category);

    }

    #[Test]
    public function destroy_delete_an_existing_user_with_200_response(): void
    {

        $route_category = Category::first();

        $show_route = $this->main_route.'/'.$route_category->id;

        //        $cloudinary_mock = $this->partialMock(
        //            CloudinaryEngine::class,
        //            function (MockInterface $mock) {
        //                \Log::info('mocking cloudinary engine');
        //                $mock->shouldReceive('destroy');
        //            }
        //        );

        $this->mockMediaRemove();

        $response = $this->delete($show_route);

        $response->assertStatus(200);

        $deleted_category = Category::query()
            ->whereId($route_category->id)
            ->first();

        $this->assertNull($deleted_category);

    }
}
