<?php

namespace App\Http\Controllers\Admin;

use App\Data\Admin\Driver\CreateDriverData;
use App\Data\Admin\Driver\DriverData;
use App\Data\Admin\Driver\PathParameters\DriverIdPathParameterData;
use App\Data\Admin\Driver\UpdateDriverData;
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
    #[OAT\Get(
        path: '/admin/drivers',
        tags: ['drivers'],
        responses: [
            new OAT\Response(
                response: 200,
                description: 'The Driver was successfully created',
                //                content: new OAT\JsonContent(ref: '#/components/schemas/paginatedDriver'),
                content: new OAT\JsonContent(
                    type: 'array',
                    items: new OAT\Items(
                        type: DriverData::class
                    ),
                ),
            ),
        ],
    )]
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

    #[OAT\Get(
        path: '/admin/drivers/{id}',
        tags: ['drivers'],
        responses: [
            new OAT\Response(
                response: 204,
                description: 'Fetched Driver Successfully',
                content: new OAT\JsonContent(type: DriverData::class),
            ),
        ],
    )]
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
    #[OAT\Post(
        path: '/admin/drivers',
        requestBody: new OAT\RequestBody(
            required: true,
            content: new OAT\JsonContent(type: CreateDriverData::class),
        ),
        tags: ['drivers'],
        responses: [
            new OAT\Response(
                response: 204,
                description: 'Driver created successfully',
                content: new OAT\JsonContent(type: DriverData::class),
            ),
        ],
    )]
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
    #[OAT\Patch(
        path: '/admin/drivers/{id}',
        requestBody: new OAT\RequestBody(
            required: true,
            content: new OAT\JsonContent(type: UpdateDriverData::class),
        ),
        tags: ['drivers'],
        responses: [
            new OAT\Response(
                response: 204,
                description: 'Driver created successfully',
                content: new OAT\JsonContent(type: DriverData::class),
            ),
        ],
    )]
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
    #[OAT\Delete(
        path: '/admin/drivers/{id}',
        tags: ['drivers'],
        responses: [
            new OAT\Response(
                response: 204,
                description: 'The Driver was successfully deleted',
            ),
        ],
    )]
    public function destroy(DriverIdPathParameterData $request): bool
    {

        Log::info('Accessing DriverController destroy method');

        $driverToDelete = User::find($request->id);

        $isDriverDeleted = $driverToDelete->delete();

        return $isDriverDeleted;

    }
}
