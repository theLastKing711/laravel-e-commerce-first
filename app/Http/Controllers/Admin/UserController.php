<?php

namespace App\Http\Controllers\Admin;

use App\Data\Admin\User\CreateUserData;
use App\Data\Admin\User\PathParameters\UserIdPathParameterData;
use App\Data\Admin\User\UpdateUserData;
use App\Data\Admin\User\UserData;
use App\Enum\Auth\RolesEnum;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use OpenApi\Attributes as OAT;

#[
    OAT\PathItem(
        path: '/admin/users/{id}',
        parameters: [
            new OAT\PathParameter(
                ref: '#/components/parameters/adminAdminIdPathParameter',
            ),
        ],
    ),
]
class UserController extends Controller
{
    private string $userRole = RolesEnum::USER->value;

    /**
     * Get All Users
     */
    #[OAT\Get(
        path: '/admin/users',
        tags: ['users'],
        responses: [
            new OAT\Response(
                response: 200,
                description: 'The User was successfully created',
                content: new OAT\JsonContent(
                    type: 'array',
                    items: new OAT\Items(
                        type: UserData::class
                    ),
                ),
            ),
        ],
    )]
    public function index()
    {

        Log::info('accessing UserController index method');

        $users = User::role($this->userRole)->select([
            'id',
            'name',
            'dial_code',
            'gender',
            'number',
            'created_at',
        ])
            ->get();

        Log::info(
            'Fetched users {users}',
            ['users' => $users]
        );

        return UserData::collect($users);
    }

    #[OAT\Get(
        path: '/admin/users/{id}',
        tags: ['users'],
        responses: [
            new OAT\Response(
                response: 200,
                description: 'Fetched User Successfully',
                content: new OAT\JsonContent(type: UserData::class),
            ),
        ],
    )]
    public function show(UserIdPathParameterData $request)
    {

        Log::info('accessing UserController show method with id {id}', ['id' => $request->id]);

        $user = User::role($this->userRole)
            ->select([
                'id',
                'name',
                'dial_code',
                'gender',
                'number',
                'created_at',
            ])
            ->find($request->id);

        Log::info('UserController show user {user}', ['user' => $user]);

        return UserData::from($user);

    }

    /**
     * Create a new User.
     */
    #[OAT\Post(
        path: '/admin/users',
        requestBody: new OAT\RequestBody(
            required: true,
            content: new OAT\JsonContent(type: CreateUserData::class),
        ),
        tags: ['users'],
        responses: [
            new OAT\Response(
                response: 201,
                description: 'User created successfully',
                content: new OAT\JsonContent(type: UserData::class),
            ),
        ],
    )]
    public function store(
        CreateUserData $createUserData,
    ) {

        Log::info('Accessing UserController store method');

        $user = User::create([
            'name' => $createUserData->name,
            'dial_code' => $createUserData->dial_code,
            'gender' => $createUserData->gender,
            'number' => $createUserData->number,
        ])
            ->assignRole($this->userRole);

        Log::info('User was created {driver}', ['driver' => $user]);

        return UserData::from($user);

    }

    /**
     * Update the specified resource in storage.
     */
    #[OAT\Patch(
        path: '/admin/users/{id}',
        requestBody: new OAT\RequestBody(
            required: true,
            content: new OAT\JsonContent(type: UpdateUserData::class),
        ),
        tags: ['users'],
        responses: [
            new OAT\Response(
                response: 200,
                description: 'User created successfully',
                content: new OAT\JsonContent(type: UserData::class),
            ),
        ],
    )]
    public function update(UserIdPathParameterData $request, UpdateUserData $updateUserData)
    {
        Log::info('Accessing UserController update method with {id}', ['id' => $request->id]);

        $user = User::find($request->id);

        $isUserUpdated = $user->update([
            'name' => $updateUserData->name,
            'dial_code' => $updateUserData->dial_code,
            'gender' => $updateUserData->gender,
            'number' => $updateUserData->number,
        ]);

        $userData = UserData::from($user);

        return $userData;

    }

    /**
     * Remove the specified resource from storage.
     */
    #[OAT\Delete(
        path: '/admin/users/{id}',
        tags: ['users'],
        responses: [
            new OAT\Response(
                response: 201,
                description: 'The User was successfully deleted',
            ),
        ],
    )]
    public function destroy(UserIdPathParameterData $request): bool
    {

        Log::info('Accessing UserController destroy method with {id}', ['id' => $request->id]);

        $userToDelete = User::find($request->id);

        $isUserDeleted = $userToDelete->delete();

        return $isUserDeleted;

    }
}
