<?php

namespace App\Http\Controllers\Admin;

use App\Data\Admin\Admin\AdminData;
use App\Data\Admin\Admin\CreateAdminData;
use App\Data\Admin\Admin\PathParameters\AdminIdPathParameterData;
use App\Data\Admin\Admin\UpdateAdminData;
use App\Data\Shared\Swagger\Request\FormDataRequestBody;
use App\Data\Shared\Swagger\Response\SuccessListResponse;
use App\Data\Shared\Swagger\Response\SuccessNoContentResponse;
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

    #[OAT\Get(path: '/admin/admin', tags: ['admin'])]
    #[SuccessListResponse(AdminData::class)]
    public function index()
    {

        Log::info('accessing Admin AdminController index method');

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

    #[OAT\Get(path: '/admin/admin/{id}', tags: ['admin'])]
    #[SuccessNoContentResponse(AdminData::class)]
    public function show(AdminIdPathParameterData $request): AdminData
    {
        Log::info('accessing Admin AdminController show method with {id}', ['id' => $request->id]);
        $admin = User::role($this->adminRole)
            ->find($request->id);

        return AdminData::from($admin);

    }

    #[OAT\Post(path: '/admin/admin', tags: ['admin'])]
    #[FormDataRequestBody(CreateAdminData::class)]
    #[SuccessNoContentResponse('User created successfully')]
    public function store(CreateAdminData $request)
    {
        Log::info('accessing Admin AdminController store method');

        $admin = User::create($request->all());

        $admin->assignRole($this->adminRole);

        $admin->save();
    }

    #[OAT\Patch(path: '/admin/admin/{id}', tags: ['admin'])]
    #[FormDataRequestBody(UpdateAdminData::class)]
    #[SuccessNoContentResponse(' Admin Updated Successfully')]
    public function update(
        AdminIdPathParameterData $request,
        UpdateAdminData $updateAdminData,
    ) {
        $admin = User::find($request->id);

        $user_updated_name = $updateAdminData->name;

        $user_updated_password = (bool) $updateAdminData->password;

        if ($user_updated_password) {

            $admin->update($request->all());

        } else {
            $admin->update([
                'name' => $updateAdminData->name,
            ]);
        }

    }

    /**
     * Remove the specified resource from storage.
     */
    #[OAT\Delete(path: '/admin/admin/{id}', tags: ['admin'])]
    #[SuccessNoContentResponse('Admin Deleted Successfully')]
    public function destroy(AdminIdPathParameterData $request): bool
    {
        $adminToDelete = User::find($request->id);

        $isAdminDeleted = $adminToDelete->delete();

        return $isAdminDeleted;

    }
}
