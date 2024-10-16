<?php

namespace App\Http\Controllers\Admin;

use App\Data\Admin\Order\OrderData;
use App\Data\Admin\Order\PathParameters\OrderIdPathParameterData;
use App\Data\Admin\Order\QueryParameters\OrderIndexQueryParameter;
use App\Data\Admin\Order\Show\OrderShowData;
use App\Data\Shared\Swagger\Parameter\QueryParameter\QueryParameter;
use App\Data\Shared\Swagger\Response\SuccessItemResponse;
use App\Data\Shared\Swagger\Response\SuccessListResponse;
use App\Data\Shared\Swagger\Response\SuccessNoContentResponse;
use App\Enum\OrderStatus;
use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Log;
use OpenApi\Attributes as OAT;

#[
    OAT\PathItem(
        path: '/admin/orders/{id}',
        parameters: [
            new OAT\PathParameter(
                ref: '#/components/parameters/adminOrderIdPathParameter',
            ),
        ],
    ),
    OAT\PathItem(
        path: '/admin/orders/{id}/changeStatus',
        parameters: [
            new OAT\PathParameter(
                ref: '#/components/parameters/adminOrderIdPathParameter',
            ),
        ],
    ),
]
class OrderController extends Controller
{
    #[OAT\Get(path: '/admin/orders', tags: ['orders'])]
    #[QueryParameter('search')]
    #[QueryParameter('order_status', OrderStatus::class)]
    #[SuccessListResponse(OrderData::class)]
    public function index(OrderIndexQueryParameter $query_parameters)
    {

        Log::info('accessing Admin OrderController index method');

        $order_status = $query_parameters->order_status?->value;

        $request_search_filter = $query_parameters->search;

        $request_search_filter_has_value = (bool) $request_search_filter;

        $request_order_status_filter_has_value = (bool) $order_status;

        $orders = Order::when(
            $request_order_status_filter_has_value,
            function (Builder $query) use ($order_status) {
                $query->where('status', $order_status);
            }
        )
            ->select([
                'user_id',
                'driver_id',
                'id',
                'status',
                'total',
            ])
            ->with([
                'user:id,name,number',
                'driver:id,name',
                'orderDetails:order_id,id,quantity,unit_price',
            ])
            ->when(
                $request_search_filter_has_value,
                function (Builder $query) use ($request_search_filter) {
                    $query
                        ->whereHas(
                            'user',
                            function (Builder $query) use ($request_search_filter) {
                                $query->whereAnyLike(
                                    ['name', 'number'],
                                    $request_search_filter
                                );
                            }
                        )
                        ->orWhereHas(
                            'driver',
                            function (Builder $query) use ($request_search_filter) {
                                $query->whereLike('name', $request_search_filter);
                            }
                        );
                }
            )
            ->get();

        return OrderData::collect($orders);

    }

    #[OAT\Get(path: '/admin/orders/{id}', tags: ['orders'])]
    #[SuccessItemResponse(OrderShowData::class)]
    public function show(OrderIdPathParameterData $path)
    {

        Log::info('accessing Admin OrderController, show method');

        $order_id = $path->id;

        $order = Order::where('id', $order_id)
            ->select([
                'id',
                'driver_id',
                'total',
                'required_time',
                'status',
                'lat',
                'lon',
                'delivery_price',
                'created_at',
            ])
            ->with([
                'orderDetails:id,order_id,product_id,quantity,unit_price' => [
                    'product' => [
                        'categories',
                    ],
                ],
                'driver:id,lat,lon',
            ])
            ->first();

        return OrderShowData::from($order);

    }

    #[OAT\Patch(path: '/admin/orders/{id}/changeStatus', tags: ['orders'])]
    #[SuccessNoContentResponse('Order status changed successfully')]
    public function changeStatus(OrderIdPathParameterData $request)
    {
        Log::info('accessing Admin OrderController, change status method');

        $order = Order::where('id', $request->id)
            ->first();

        $order->update([
            'status' => $request->status,
        ]);

    }
}
