<?php

namespace App\Http\Controllers\Admin;

use App\Data\Admin\User\CreateUserData;
use App\Data\Admin\User\PathParameters\UserIdPathParameterData;
use App\Data\Admin\User\UpdateUserData;
use App\Data\Admin\User\UserData;
use App\Data\Shared\Swagger\Request\JsonRequestBody;
use App\Data\Shared\Swagger\Response\SuccessItemResponse;
use App\Data\Shared\Swagger\Response\SuccessListResponse;
use App\Data\Shared\Swagger\Response\SuccessNoContentResponse;
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

    #[OAT\Get(path: '/admin/users', tags: ['users'])]
    #[SuccessListResponse(UserData::class)]
    public function index()
    {

        Log::info('accessing Admin UserController index method');

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


    #[OAT\Get(path: '/admin/users/{id}', tags: ['users'])]
    #[SuccessItemResponse(UserData::class)]
    public function show(UserIdPathParameterData $request)
    {

        Log::info('accessing Admin UserController show method with id {id}', ['id' => $request->id]);

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

    #[OAT\Post(path: '/admin/users', tags: ['users'])]
    #[JsonRequestBody(CreateUserData::class)]
    #[SuccessNoContentResponse()]
    public function store(
        CreateUserData $createUserData,
    ) {

        Log::info('Accessing Admin UserController store method');

        $user = User::create([
            'name' => $createUserData->name,
            'dial_code' => $createUserData->dial_code,
            'gender' => $createUserData->gender,
            'number' => $createUserData->number,
        ])
            ->assignRole($this->userRole);

        Log::info('User was created {driver}', ['driver' => $user]);


    }

    /**
     * Update the specified resource in storage.
     */

    #[OAT\Patch(path: '/admin/users/{id}', tags: ['users'])]
    #[JsonRequestBody(UpdateUserData::class)]
    #[SuccessNoContentResponse()]
    public function update(UserIdPathParameterData $request, UpdateUserData $updateUserData)
    {
        Log::info('Accessing Admin UserController update method with {id}', ['id' => $request->id]);

        $user = User::find($request->id);

        $isUserUpdated = $user->update([
            'name' => $updateUserData->name,
            'dial_code' => $updateUserData->dial_code,
            'gender' => $updateUserData->gender,
            'number' => $updateUserData->number,
        ]);

        $userData = UserData::from($user);


    }

    /**
     * Remove the specified resource from storage.
     */

    #[OAT\Delete(path: '/admin/users/{id}', tags: ['users'])]
    #[SuccessNoContentResponse('Item Deleted Successfully')]
    public function destroy(UserIdPathParameterData $request): bool
    {

        Log::info('Accessing Admin UserController destroy method with {id}', ['id' => $request->id]);

        $userToDelete = User::find($request->id);

        $isUserDeleted = $userToDelete->delete();

        return $isUserDeleted;

    }
}
