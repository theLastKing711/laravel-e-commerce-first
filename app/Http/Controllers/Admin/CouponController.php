<?php

namespace App\Http\Controllers\Admin;

use App\Data\Admin\Coupon\CreateCouponData;
use App\Data\Admin\Coupon\CouponData;
use App\Data\Admin\Coupon\PathParameters\CouponIdPathParameterData;
use App\Data\Admin\Coupon\UpdateCouponData;
use App\Data\Shared\Swagger\Request\JsonRequestBody;
use App\Data\Shared\Swagger\Response\SuccessItemResponse;
use App\Data\Shared\Swagger\Response\SuccessListResponse;
use App\Data\Shared\Swagger\Response\SuccessNoContentResponse;
use App\Http\Controllers\Controller;
use App\Models\Coupon;
use App\Models\Group;
use App\Models\User;
use Illuminate\Http\Request;
use Log;
use OpenApi\Attributes as OAT;

#[
    OAT\PathItem(
        path: '/admin/coupons/{id}',
        parameters: [
            new OAT\PathParameter(
                ref: '#/components/parameters/adminCouponIdPathParameter',
            ),
        ],
    ),
]
class CouponController extends Controller
{

    #[OAT\Get(path: '/admin/coupons', tags: ['coupons'])]
    #[SuccessListResponse(CouponData::class, 'The Coupons were successfully fetched')]
    public function index()
    {

        Log::info('accessing Admin CouponController index method');

        return CouponData::collect(
            Coupon::with('users:id,name,number')
                ->select([
                    'id',
                    'name',
                    'code',
                    'percent',
                    'value',
                    'start_at',
                    'end_at',
                    'created_at',
                ])
                ->get()
        );
    }


    #[OAT\Get(path: '/admin/coupons/{id}', tags: ['coupons'])]
    #[SuccessItemResponse(CouponData::class, 'The Coupon was successfully fetched')]
    public function show(CouponIdPathParameterData $request): CouponData
    {

        $coupon = Coupon::with('users:id,name,number')
            ->where('id', $request->id)
            ->select([
                'id',
                'name',
                'code',
                'percent',
                'value',
                'start_at',
                'end_at',
                'created_at',
            ])
            ->first();

        return CouponData::from(
            $coupon
        );

    }

    /**
     * Update the specified resource in storage.
     */

    #[OAT\Patch(path: '/admin/coupons/{id}', tags: ['coupons'])]
    #[JsonRequestBody(UpdateCouponData::class)]
    #[SuccessNoContentResponse('The Coupon was updated successfully')]
    public function update(
        Request $request,
        UpdateCouponData $updatedCouponData,
    ): bool {

        Log::info('accessing CouponController update method');

        $coupon = Coupon::find($request->id);

        $userIdsFromRequestGroupIds = Group::userIds($updatedCouponData->group_ids);

        $couponUserIdsToSync = $userIdsFromRequestGroupIds
            ->merge($request->user_ids)
            ->unique();

        $coupon->update([
            'id' => $request->id,
            'name' => $updatedCouponData->name,
            'code' => $updatedCouponData->code,
            'percent' => $updatedCouponData->percent,
            'start_at' => $updatedCouponData->start_at,
            'end_at' => $updatedCouponData->end_at,
            'value' => $updatedCouponData->value,
        ]);

        $coupon->users()->sync($couponUserIdsToSync);

        return true;
    }


    #[OAT\Post(path: '/admin/coupons/{id}', tags: ['coupons'])]
    #[JsonRequestBody(CreateCouponData::class)]
    #[SuccessNoContentResponse('The Coupon was created successfully')]
    public function store(CreateCouponData $createCouponData): bool
    {

        Log::info('accessing CouponController store method');

        $userIdsFromRequestGroupIds = Group::userIds($createCouponData->group_ids);

        $couponUserIdsToAdd = $userIdsFromRequestGroupIds
            ->merge($createCouponData->user_ids)
            ->unique();

        $coupon = Coupon::create([
            'name' => $createCouponData->name,
            'code' => $createCouponData->code,
            'percent' => $createCouponData->percent,
            'start_at' => $createCouponData->start_at,
            'end_at' => $createCouponData->end_at,
            'value' => $createCouponData->value,
        ]);

        $coupon->users()->attach($couponUserIdsToAdd);

        return true;
    }

    /**
     * Remove the specified resource from storage.
     */

    #[OAT\Delete(path: '/admin/coupons/{id}', tags: ['coupons'])]
    #[SuccessNoContentResponse('The Coupon was deleted successfully')]
    public function destroy(CouponIdPathParameterData $request): bool
    {

        User::with('lastname');

        Log::info('Accessing CouponController destroy method');

        $couponToDelete = Coupon::find($request->id);

        $isCouponDeleted = $couponToDelete->delete();

        return $isCouponDeleted;

    }
}
