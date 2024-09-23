<?php

namespace App\Http\Controllers\Admin;

use App\Data\Admin\Group\CreateGroupData;
use App\Data\Admin\Group\GroupData;
use App\Data\Admin\Group\PathParameters\GroupIdPathParameterData;
use App\Data\Admin\Group\UpdateGroupData;
use App\Data\Shared\Swagger\Request\JsonRequestBody;
use App\Data\Shared\Swagger\Response\SuccessItemResponse;
use App\Data\Shared\Swagger\Response\SuccessListResponse;
use App\Data\Shared\Swagger\Response\SuccessNoContentResponse;
use App\Enum\Auth\RolesEnum;
use App\Http\Controllers\Controller;
use App\Models\Group;
use Illuminate\Support\Facades\Log;
use OpenApi\Attributes as OAT;

#[
    OAT\PathItem(
        path: '/admin/groups/{id}',
        parameters: [
            new OAT\PathParameter(
                ref: '#/components/parameters/adminGroupIdPathParameter',
            ),
        ],
    ),
]
class GroupController extends Controller
{
    private string $userRole = RolesEnum::USER->value;

    /**
     * Get All Groups
     */

    #[OAT\Get(path: '/admin/groups', tags: ['groups'])]
    #[SuccessListResponse(GroupData::class, 'The Groups were successfully fetched')]
    public function index()
    {

        Log::info('accessing GroupController index method');

        $groups = Group::select([
            'id',
            'name',
            'created_at',
        ])
            ->get();

        Log::info(
            'Fetched groups {groups}',
            ['groups' => $groups]
        );

        return GroupData::collect($groups);
    }


    #[OAT\Get(path: '/admin/groups/{id}', tags: ['groups'])]
    #[SuccessItemResponse(GroupData::class, 'The Group was successfully fetched')]
    public function show(GroupIdPathParameterData $request)
    {
        Log::info('group id {id}', ['id' => $request]);
        $user = Group::select([
            'id',
            'name',
            'created_at',
        ])
            ->find($request->id);

        return GroupData::from($user);

    }

    /**
     * Create a new Group.
     */
    #[OAT\Post(path: '/admin/groups', tags: ['groups'])]
    #[JsonRequestBody(CreateGroupData::class)]
    #[SuccessNoContentResponse('Group created successfully')]
    public function store(
        CreateGroupData $createGroupData,
    ): GroupData {

        Log::info('Accessing GroupController store method');

        $user = Group::create([
            'name' => $createGroupData->name,

        ]);

        Log::info('Group was created {group}', ['group' => $user]);

    }


    /**
     * Update the specified resource in storage.
     */

    #[OAT\Patch(path: '/admin/groups/{id}', tags: ['groups'])]
    #[JsonRequestBody(UpdateGroupData::class)]
    #[SuccessNoContentResponse('The Group was updated successfully')]
    public function update(GroupIdPathParameterData $request, UpdateGroupData $updateGroupData): GroupData
    {
        Log::info('Accessing GroupController update method');

        $user = Group::find($request->id);

        $isGroupUpdated = $user->update([
            'name' => $updateGroupData->name,
        ]);

        return GroupData::from($user);

    }

    /**
     * Remove the specified resource from storage.
     */

    #[OAT\Delete(path: '/admin/groups/{id}', tags: ['groups'])]
    #[SuccessNoContentResponse('The Group was deleted successfully')]
    public function destroy(GroupIdPathParameterData $request): bool
    {

        Log::info('Accessing GroupController destroy method');

        $userToDelete = Group::find($request->id);

        $isGroupDeleted = $userToDelete->delete();

        return $isGroupDeleted;

    }
}
