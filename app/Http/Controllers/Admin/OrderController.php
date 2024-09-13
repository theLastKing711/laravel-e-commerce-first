<?php

namespace App\Http\Controllers\Admin;

use App\Data\Admin\Order\OrderData;
use App\Data\Admin\Order\PathParameters\OrderIdPathParameterData;
use App\Data\Admin\Order\QueryParameters\OrderSearchQueryParameterData;
use App\Data\Admin\Order\QueryParameters\OrderStatusQueryParameterData;
use App\Data\Admin\Order\Show\OrderShowData;
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
    #[OAT\Get(
        path: '/admin/orders',
        tags: ['orders'],
        parameters: [
            new OAT\QueryParameter(
                required: false,
                ref: '#/components/parameters/adminOrderSearch',
            ),
            new OAT\QueryParameter(
                required: false,
                ref: '#/components/parameters/adminOrderStatus',
            ),
        ],
        responses: [
            new OAT\Response(
                response: 200,
                description: 'The Order was successfully created',
                content: new OAT\JsonContent(
                    type: 'array',
                    items: new OAT\Items(
                        type: OrderData::class
                    ),
                ),
            ),
        ],
    )]
    public function index(OrderSearchQueryParameterData $search_query, OrderStatusQueryParameterData $status_query)
    {


        Log::info('accessing OrderController index method');

        $order_status = $status_query->order_status?->value;

        $search = $search_query->search;

        $is_search_filter_available = (bool) $search;

        $is_order_status_filter_available = (bool) $order_status;

        $orders = Order::when($is_order_status_filter_available, function (Builder $query) use ($order_status) {
            $query->where('status', $order_status);
        })
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
            ->when($is_search_filter_available, function (Builder $query) use ($search) {
                $query
                    ->whereHas('user', function (Builder $query) use ($search) {
                        $query->whereAny(
                            ['name', 'number'],
                            'LIKE',
                            '%'.$search.'%'
                        );
                    })
                    ->orWhereHas('driver', function (Builder $query) use ($search) {
                        $query->where('name', 'LIKE', '%'.$search.'%');
                    });
            })
            ->get();

        Log::info($orders);

        return OrderData::collect($orders);

    }

    #[OAT\Get(
        path: '/admin/orders/{id}',
        tags: ['orders'],
        parameters: [
            new OAT\QueryParameter(
                required: false,
                ref: '#/components/parameters/adminOrderSearch',
            ),
            new OAT\QueryParameter(
                required: false,
                ref: '#/components/parameters/adminOrderStatus',
            ),
        ],
        responses: [
            new OAT\Response(
                response: 200,
                description: 'The Order was successfully created',
                content: new OAT\JsonContent(
                    type: 'array',
                    items: new OAT\Items(
                        type: OrderData::class
                    ),
                ),
            ),
        ],
    )]
    public function show(OrderIdPathParameterData $path)
    {

        Log::info('accessing OrderController index method');

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
                    'product:image',
                ],
                'driver:id,lat,lon',
                'orderDetails.product.categories',
            ])
            ->first();

        return OrderShowData::from($order);

    }

    #[OAT\Patch(
        path: '/admin/orders/{id}/changeStatus',
        tags: ['orders'],
        responses: [
            new OAT\Response(
                response: 200,
                description: 'Order Status Changed Successfully',
                content: new OAT\JsonContent(type: OrderData::class),
            ),
        ],
    )]
    public function changeStatus(OrderIdPathParameterData $request)
    {

        $order = Order::where('id', $request->id)
            ->first();

        $order->update([
            'status' => $request->status,
        ]);

        return $order;

    }
}
