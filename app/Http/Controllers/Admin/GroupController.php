<?php

namespace App\Http\Controllers\Admin;

use App\Data\Admin\Group\CreateGroupData;
use App\Data\Admin\Group\GroupData;
use App\Data\Admin\Group\PathParameters\GroupIdPathParameterData;
use App\Data\Admin\Group\UpdateGroupData;
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
    #[OAT\Get(
        path: '/admin/groups',
        tags: ['groups'],
        responses: [
            new OAT\Response(
                response: 200,
                description: 'The Group was successfully created',
                content: new OAT\JsonContent(
                    type: 'array',
                    items: new OAT\Items(
                        type: GroupData::class
                    ),
                ),
            ),
        ],
    )]
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

    #[OAT\Get(
        path: '/admin/groups/{id}',
        tags: ['groups'],
        responses: [
            new OAT\Response(
                response: 204,
                description: 'Fetched Group Successfully',
                content: new OAT\JsonContent(type: GroupData::class),
            ),
        ],
    )]
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
    #[OAT\Post(
        path: '/admin/groups',
        requestBody: new OAT\RequestBody(
            required: true,
            content: new OAT\JsonContent(type: CreateGroupData::class),
        ),
        tags: ['groups'],
        responses: [
            new OAT\Response(
                response: 204,
                description: 'Group created successfully',
                content: new OAT\JsonContent(type: GroupData::class),
            ),
        ],
    )]
    public function store(
        CreateGroupData $createGroupData,
    ): GroupData {

        Log::info('Accessing GroupController store method');

        $user = Group::create([
            'name' => $createGroupData->name,

        ]);

        Log::info('Group was created {group}', ['group' => $user]);

        return GroupData::from($user);
    }


    /**
     * Update the specified resource in storage.
     */
    #[OAT\Patch(
        path: '/admin/groups/{id}',
        requestBody: new OAT\RequestBody(
            required: true,
            content: new OAT\JsonContent(type: UpdateGroupData::class),
        ),
        tags: ['groups'],
        responses: [
            new OAT\Response(
                response: 204,
                description: 'Group created successfully',
                content: new OAT\JsonContent(type: GroupData::class),
            ),
        ],
    )]
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
    #[OAT\Delete(
        path: '/admin/groups/{id}',
        tags: ['groups'],
        responses: [
            new OAT\Response(
                response: 204,
                description: 'The Group was successfully deleted',
            ),
        ],
    )]
    public function destroy(GroupIdPathParameterData $request): bool
    {

        Log::info('Accessing GroupController destroy method');

        $userToDelete = Group::find($request->id);

        $isGroupDeleted = $userToDelete->delete();

        return $isGroupDeleted;

    }
}
