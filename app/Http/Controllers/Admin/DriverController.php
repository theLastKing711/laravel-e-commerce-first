<?php

namespace App\Http\Controllers\Admin;

use App\Data\Admin\Driver\CreateDriverData;
use App\Data\Admin\Driver\DriverData;
use App\Data\Admin\Driver\PathParameters\DriverIdPathParameterData;
use App\Data\Admin\Driver\UpdateDriverData;
use App\Data\Shared\Swagger\Request\JsonRequestBody;
use App\Data\Shared\Swagger\Response\SuccessItemResponse;
use App\Data\Shared\Swagger\Response\SuccessListResponse;
use App\Data\Shared\Swagger\Response\SuccessNoContentResponse;
use App\Enum\Auth\RolesEnum;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use OpenApi\Attributes as OAT;
use Revolt\EventLoop\Driver;

#[
    OAT\PathItem(
        path: '/admin/drivers/{id}',
        parameters: [
            new OAT\PathParameter(
                ref: '#/components/parameters/adminDriverIdPathParameter',
            ),
        ],
    ),
]
class DriverController extends Controller
{
    private string $driverRole = RolesEnum::DRIVER->value;

    /**
     * Get All Drivers
     */

    #[OAT\Get(path: '/admin/drivers', tags: ['drivers'])]
    #[SuccessListResponse(DriverData::class, 'The Drivers were successfully fetched')]
    public function index()
    {

        Log::info('accessing DriverController index method');

        $driversData = User::role($this->driverRole)->select([
            'id',
            'name',
            'username',
            'number',
            'created_at',
        ])
            ->get()
            ->map(fn (User $user) => new DriverData(
                id: $user->id,
                name: $user->name,
                username: $user->username,
                number: $user->number,
                created_at: $user->created_at,
            ));

        Log::info(
            'Fetched drivers {drivers}',
            ['drivers' => $driversData]
        );

        return $driversData;
    }


    #[OAT\Get(path: '/admin/drivers/{id}', tags: ['drivers'])]
    #[SuccessItemResponse('The Driver was successfully fetched')]
    public function show(DriverIdPathParameterData $request)
    {
        Log::info('driver id {id}', ['id' => $request]);
        $driver = User::role($this->driverRole)
            ->select([
                'id',
                'name',
                'username',
                'number',
                'created_at',
            ])
            ->find($request->id);

        return DriverData::from($driver);

    }

    /**
     * Create a new Driver.
     */

    #[OAT\Post(path: '/admin/drivers/{id}', tags: ['drivers'])]
    #[JsonRequestBody(CreateDriverData::class)]
    #[SuccessNoContentResponse('The Driver was created successfully')]
    public function store(
        CreateDriverData $createDriverData,
    ): DriverData {

        Log::info('Accessing DriverController store method');

        $driver = User::create([
            'name' => $createDriverData->name,
            'username' => $createDriverData->username,
            'password' => $createDriverData->password,
            'number' => $createDriverData->number,
        ])
            ->assignRole($this->driverRole);

        Log::info('Driver was created {driver}', ['driver' => $driver]);

        return DriverData::from($driver);
    }

    /**
     * Update the specified resource in storage.
     */

    #[OAT\Patch(path: '/admin/drivers/{id}', tags: ['drivers'])]
    #[JsonRequestBody(UpdateDriverData::class)]
    #[SuccessNoContentResponse('The Driver was updated successfully')]
    public function update(DriverIdPathParameterData $request, UpdateDriverData $updateDriverData): DriverData
    {
        Log::info('Accessing DriverController update method');

        $driver = User::find($request->id);

        $isDriverUpdated = $driver->update([
            'name' => $updateDriverData->name,
            'username' => $updateDriverData->username,
            'password' => $updateDriverData->password,
            'number' => $updateDriverData->number,
        ]);


        return DriverData::from($driver);

    }

    /**
     * Remove the specified resource from storage.
     */

    #[OAT\Delete(path: '/admin/drivers/{id}', tags: ['drivers'])]
    #[SuccessNoContentResponse('The Driver was deleted successfully')]
    public function destroy(DriverIdPathParameterData $request): bool
    {

        Log::info('Accessing DriverController destroy method');

        $driverToDelete = User::find($request->id);

        $isDriverDeleted = $driverToDelete->delete();

        return $isDriverDeleted;

    }
}
