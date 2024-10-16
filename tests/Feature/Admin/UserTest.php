<?php

namespace Tests\Feature\Admin;

use App\Data\Admin\User\CreateUserData;
use App\Data\Admin\User\UpdateUserData;
use App\Data\Admin\User\UserData;
use App\Enum\Auth\RolesEnum;
use App\Enum\Gender;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use PHPUnit\Framework\Attributes\Test;
use Tests\Feature\Admin\Abstractions\AdminTestCase;

class UserTest extends AdminTestCase
{
    private string $main_route = '/admin/users';

    public User $user;

    private function CreateUser(): void
    {
        $this->user = User::factory()
            ->user()
            ->create();
    }

    public function setUp(): void
    {
        parent::setUp();

        $this->CreateUser();

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

        $createUserRequestData = new CreateUserData(
            name: 'new user',
            dial_code: '+963',
            gender: Gender::Male,
            number: '1234567890',
        );

        $response = $this->post(
            $this->main_route,
            $createUserRequestData->toArray()
        );

        $response->assertStatus(200);

        $created_user = User::with('roles')
            ->orderBy('id', 'desc')
            ->first();

        Log::info('created user {user}', ['user' => User::all()]);

        $user_has_user_role = $created_user->hasRole(RolesEnum::USER->value);

        $this->assertTrue($user_has_user_role);

        $this->assertEquals(
            [
                'name' => $created_user->name,
                'gender' => $created_user->gender->value,
                'dial_code' => $created_user->dial_code,
                'number' => $created_user->number,
            ],
            $createUserRequestData->toArray()
        );

        $created_user = User::query()
            ->whereName($created_user->name)
            ->first();

        $this->assertNotNull($created_user);
    }

    #[Test]
    public function update_update_an_existing_user_with_201_response(): void
    {
        $user_path_id = $this->user->id;

        $show_route = $this->main_route.'/'.$user_path_id;

        $request_update_user_data = new UpdateUserData(
            name: 'updatedName',
            dial_code: '+964',
            gender: Gender::Male,
            number: '5234567890',
        );

        $response = $this->patch($show_route, $request_update_user_data->toArray());

        $response->assertStatus(200);

        $updated_user = User::query()
            ->role(RolesEnum::USER->value)
            ->whereId($user_path_id)
            ->first();

        $this->assertEquals(
            [
                'name' => $updated_user->name,
                'gender' => $updated_user->gender->value,
                'dial_code' => $updated_user->dial_code,
                'number' => $updated_user->number,
            ],
            $request_update_user_data->toArray()
        );

        $updated_user = User::query()
            ->whereName($request_update_user_data->name)
            ->whereDialCode($request_update_user_data->dial_code)
            ->whereNumber($request_update_user_data->number)
            ->first();

        $this->assertNotNull($updated_user);

    }

    #[Test]
    public function destroy_delete_an_existing_user_with_200_response(): void
    {
        $user_path_id = $this->user->id;

        $show_route = $this->main_route.'/'.$user_path_id;

        $response = $this->delete($show_route);

        $response->assertStatus(200);

        $deleted_user = User::role(RolesEnum::USER->value)
            ->whereId($user_path_id)
            ->first();

        $this->assertNull($deleted_user);

    }
}
