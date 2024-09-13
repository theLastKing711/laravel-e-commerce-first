<?php

namespace Tests\Feature\Admin;

use App\Data\Admin\User\CreateUserData;
use App\Data\Admin\User\UpdateUserData;
use App\Data\Admin\User\UserData;
use App\Enum\Auth\RolesEnum;
use App\Enum\Gender;
use App\Models\User;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Log;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    private string $main_route = '/admin/users';

    private User $user;

    public function setUp(): void
    {
        parent::setUp();

        $this->seed(RolesAndPermissionsSeeder::class);

        $this->CreateAdmin();
        $this->CreateUser();

        $this->actingAs($this->admin);
    }

    public function CreateAdmin(): void
    {
        $this->admin =
            User::factory()
                ->admin()
                ->create();
    }

    public function CreateUser(): void
    {
        $this->user = User::factory()
            ->user()
            ->create();
    }

    /**
     * A basic feature test example.
     */
    #[Test]
    public function index_return_a_list_of_users_with_200_response(): void
    {

        $response = $this->get($this->main_route);

        Log::info('response {response}', ['response' => $response->original]);

        $response->assertStatus(200);

    }

    #[Test]
    public function show_user_using_path_id_path_with_200_response(): void
    {

        $show_route = $this->main_route.'/'.$this->user->id;

        $response = $this->get($show_route);

        Log::info('response {response}', ['response' => $response->original]);

        $response->assertStatus(200);

        $userData = UserData::from($this->user);

        Log::info('userData {data}', ['data' => $userData->toArray()]);

        $response->assertExactJson($userData->toArray());

    }

    #[Test]
    public function store_create_a_new_user_with_201_response(): void
    {

        $createUserRequestData = CreateUserData::from([
            'name' => 'new user',
            'email' => 'newuser@example.com',
            'gender' => Gender::Male,
            'dial_code' => '+963',
            'number' => '1234567890',
        ]);

        $response = $this
            ->withHeader('Accept', 'application/json')
            ->post($this->main_route, $createUserRequestData->toArray());

        $response->assertStatus(201);

        $user = User::role(RolesEnum::USER->value)
            ->where('name', $createUserRequestData->name)
            ->first();

        $userData = UserData::from($user);

        $response->assertExactJson($userData->toArray());

    }

    #[Test]
    public function update_update_an_existing_user_with_201_response(): void
    {
        $user_path_id = $this->user->id;

        $show_route = $this->main_route.'/'.$user_path_id;

        $updateUserRequestData = UpdateUserData::from([
            'name' => 'updatedName',
            'email' => 'newuser@example.com',
            'dial_code' => '+964',
            'number' => '5234567890',
            'gender' => Gender::Male->value,
        ]);

        $response = $this
            ->withHeader('Accept', 'application/json')
            ->patch($show_route, $updateUserRequestData->toArray());

        $response->assertStatus(200);

        $user = User::role(RolesEnum::USER->value)
            ->find($user_path_id);

        $userData = UserData::from($user);

        Log::info('userData {data}', ['data' => $userData->toArray()]);

        $response->assertExactJson($userData->toArray());

    }

    #[Test]
    public function destroy_delete_an_existing_user_with_201_response(): void
    {
        $user_path_id = $this->user->id;

        $show_route = $this->main_route.'/'.$user_path_id;

        $response = $this
            ->withHeader('Accept', 'application/json')
            ->delete($show_route);

        $response->assertStatus(200);

        $user = User::role(RolesEnum::USER->value)
            ->find($user_path_id);

        $this->assertNull($user);

    }
}
