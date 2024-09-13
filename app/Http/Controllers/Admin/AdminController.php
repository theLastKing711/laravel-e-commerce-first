<?php

namespace App\Http\Controllers\Admin;

use App\Data\Admin\Admin\AdminData;
use App\Data\Admin\Admin\CreateAdminData;
use App\Data\Admin\Admin\PathParameters\AdminIdPathParameterData;
use App\Enum\Auth\RolesEnum;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use OpenApi\Attributes as OAT;

#[
    OAT\PathItem(
        path: '/admin/admin/{id}',
        parameters: [
            new OAT\PathParameter(
                ref: '#/components/parameters/adminAdminIdPathParameter',
            ),
            new OAT\QueryParameter(
                ref: '#/components/parameters/localeQueryParameter',
            ),
        ],
    ),
    OAT\PathItem(
        path: '/admin/admin',
        parameters: [
            new OAT\QueryParameter(
                ref: '#/components/parameters/localeQueryParameter',
            ),
        ],
    ),
]
class AdminController extends Controller
{
    private string $adminRole = RolesEnum::ADMIN->value;

    #[OAT\Get(
        path: '/admin/admin',
        tags: ['admin'],
        responses: [
            new OAT\Response(
                response: 200,
                description: 'The Admin was successfully created',
                //                content: new OAT\JsonContent(ref: '#/components/schemas/paginatedCategory'),
                content: new OAT\JsonContent(
                    type: 'array',
                    items: new OAT\Items(
                        type: AdminData::class
                    ),
                ),
            ),
        ],
    )]
    public function index()
    {

        Log::info('accessing AdminController index method');

        $adminData = AdminData::collect(
            User::role($this->adminRole)
                ->select([
                    'id',
                    'name',
                    'email',
                    'created_at',
                ])
                ->get()
        );

        Log::info(
            'Fetched admin {admin}',
            ['admin' => $adminData]
        );

        return $adminData;
    }

    #[OAT\Get(
        path: '/admin/admin/{id}',
        tags: ['admin'],
        responses: [
            new OAT\Response(
                response: 204,
                description: 'Fetched Admin Successfully',
                content: new OAT\JsonContent(type: AdminData::class),
            ),
        ],
    )]
    public function show(AdminIdPathParameterData $request): AdminData
    {
        Log::info('user id {id}', ['id' => $request]);
        $admin = User::role($this->adminRole)
            ->find($request->id);

        return AdminData::from($admin);

    }

    #[OAT\Post(
        path: '/admin/admin',
        requestBody: new OAT\RequestBody(
            required: true,
            content: new OAT\JsonContent(type: CreateAdminData::class),
        ),
        tags: ['admin'],
        responses: [
            new OAT\Response(
                response: 204,
                description: 'User created successfully',
                content: new OAT\JsonContent(type: AdminData::class),
            ),
        ],
    )]
    public function store(CreateAdminData $request): AdminData
    {
        Log::info('accessing AdminController store method');

        $admin = User::create($request->all());

        $admin->assignRole($this->adminRole);

        $admin->save();

        return AdminData::from($admin);
    }
}
