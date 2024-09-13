<?php

namespace App\Http\Controllers\User;

use App\Data\User\Order\Create\CreateOrderData;
use App\Data\User\Order\Index\OrderData;
use App\Data\User\Order\PathParameters\OrderIdPathParameterData;
use App\Data\User\Order\QueryParameters\OrderProcessedQueryParameterData;
use App\Data\User\Order\Show\OrderShowData;
use App\Enum\OrderStatus;
use App\Http\Controllers\Controller;
use App\Models\Coupon;
use App\Models\Order;
use App\Models\OrderDetails;
use App\Models\Product;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use OpenApi\Attributes as OAT;

#[
    OAT\PathItem(
        path: '/user/orders/{id}',
        parameters: [
            new OAT\PathParameter(
                ref: '#/components/parameters/userOrderIdPathParameter',
            ),
        ],
    ),
    //    OAT\PathItem(
    //        path: '/user/orders/{id}/changeStatus',
    //        parameters: [
    //            new OAT\PathParameter(
    //                ref: '#/components/parameters/userOrderIdPathParameter',
    //            ),
    //        ],
    //    ),
]
class OrderController extends Controller
{
    #[OAT\Get(
        path: '/user/orders/{id}',
        tags: ['userOrders'],
        responses: [
            new OAT\Response(
                response: 200,
                description: 'category fetched successfully',
                content: new OAT\JsonContent(type: OrderShowData::class),
            ),
        ],
    )]
    public function show(Order $order)
    {

        Log::info(
            'accessing User OrderController show method with path id {id}',
            ['id' => $order->id]
        );

        $order = Order::query()
            ->where('id', $order->id)
            ->with([
                'orderDetails' => [
                    'product',
                ],
                'driver',
                'user',
            ])
            ->get();

        return OrderShowData::collect($order);
    }

    /**
     * Get All Orders.
     */
    #[OAT\Get(
        path: '/user/orders',
        tags: ['userOrders'],
        parameters: [
            new OAT\QueryParameter(
                ref: '#/components/parameters/userOrderProcessed',
            ),
        ],
        responses: [
            new OAT\Response(
                response: 200,
                description: 'Order Data Fetched Successfully',
                content: new OAT\JsonContent(
                    type: 'array',
                    items: new OAT\Items(
                        type: OrderData::class
                    ),
                ),
            ),
        ],
    )]
    public function index(OrderProcessedQueryParameterData $query_param)
    {

        Log::info('accessing User OrderController index method');

        $authenticatedUser = auth()->user();

        $is_order_processed = $query_param->is_order_processed;

        $orders = Order::query()
            ->with(['orderDetails', 'coupon'])
            ->where('user_id', $authenticatedUser->id)
            ->when(! $is_order_processed, function (Builder $query) {
                $un_processed_order_statuses = [
                    OrderStatus::Pending->value,
                    OrderStatus::OnTheWay->value,
                    OrderStatus::DriverAccept->value,
                    OrderStatus::Accepted->value,
                ];

                $query->whereIn('status', $un_processed_order_statuses);
            })
            ->when($is_order_processed, function (Builder $query) {
                $processed_order_statuses = [
                    OrderStatus::Completed->value,
                    OrderStatus::Rejected->value,
                ];
                $query->whereIn('status', $processed_order_statuses);
            })
            ->get();

        return OrderData::collect($orders);

    }

    /**
     * Create a new Order.
     */
    #[OAT\Post(
        path: '/user/orders',
        requestBody: new OAT\RequestBody(
            required: true,
            content: new OAT\JsonContent(type: CreateOrderData::class),
        ),
        tags: ['userOrders'],
        responses: [
            new OAT\Response(
                response: 201,
                description: 'User created successfully',
                content: new OAT\JsonContent(type: 'boolean'),
            ),
        ],
    )]
    public function store(
        CreateOrderData $createUserData,
    ) {

        Log::info('Accessing User OrderController store method');

        $applied_coupon_id = Coupon::where('code', $createUserData->code)
            ->first()
            ->id;

        $order_products = Product::whereIn('id', $createUserData->order_details->pluck('product_id')->unique())
            ->get();

        $order_details = $createUserData
            ->order_details
            ->groupBy('product_id')
            ->map(function (
                Collection $order_details,
                string $key
            ) use ($order_products) {

                $product = $order_products
                    ->where('id', $key)
                    ->first();

                $price = $product->price_offer ?? $product->price;

                return new OrderDetails([
                    'product_id' => $key,
                    'unit_price' => $product->price,
                    'unit_price_offer' => $product->price_offer,
                    'quantity' => $order_details->sum('quantity'),
                ]);
            });

        $order_total = $order_details
            ->sum(function (OrderDetails $item) {

                $price = $item->unit_price ?? $item->unit_price_offer;

                return $price * $item->quantity;
            });

        $order = Order::create([
            'user_id' => auth()->id(),
            'coupon_id' => $applied_coupon_id,
            'notice' => $createUserData->notice,
            'required_time' => $createUserData->required_time,
            'total' => $order_total,
            'status' => OrderStatus::Pending,
            'lat' => 0,
            'lon' => 0,
            'delivery_price' => 200,
        ]);

        $order->orderDetails()->saveMany($order_details);

        return true;

    }
}
