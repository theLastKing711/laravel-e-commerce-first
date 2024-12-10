<?php

namespace App\Http\Controllers\User;

use App\Data\Shared\Swagger\Property\QueryParameter;
use App\Data\Shared\Swagger\Request\JsonRequestBody;
use App\Data\Shared\Swagger\Response\SuccessItemResponse;
use App\Data\Shared\Swagger\Response\SuccessListResponse;
use App\Data\User\Order\Create\CreateOrderData;
use App\Data\User\Order\Index\OrderData;
use App\Data\User\Order\QueryParameters\OrderProcessedQueryParameterData;
use App\Data\User\Order\Show\OrderShowData;
use App\Enum\OrderStatus;
use App\Http\Controllers\Controller;
use App\Models\Coupon;
use App\Models\Order;
use App\Models\OrderDetails;
use App\Models\Product;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
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
]
class OrderController extends Controller
{
    #[OAT\Get(path: '/user/orders/{id}', tags: ['userOrders'])]
    #[SuccessItemResponse(OrderShowData::class)]
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
    #[OAT\Get(path: '/user/orders', tags: ['userOrders'])]
    #[QueryParameter('is_order_processed', 'boolean')]
    #[SuccessListResponse(OrderData::class)]
    public function index(OrderProcessedQueryParameterData $query_param)
    {
        Log::info('accessing User OrderController index method');

        $authenticatedUser = auth()->user();
        $request_has_order_processed_filter = $query_param->is_order_processed;

        $orders = Order::query()
            ->select(['id', 'status as order_status', 'required_time', 'created_at'])
            ->whereUserId($authenticatedUser->id)
            ->addSelect(
                [
                    'total' => OrderDetails::query()

                        ->whereColumn('order_id', 'order_details.id')
                        ->join('products', 'order_details.product_id', 'products.id')
                        ->selectRaw('SUM(
                                    COALESCE(products.price, products.price_offer) * order_details.quantity
                                )'),
                    //                    'order_details_sum_quantity' => OrderDetails::query()
                    //                        ->whereColumn('order_id', 'order_details.id')
                    //                        ->join('products', 'order_details.product_id', 'products.id')
                    //                        ->selectRaw('SUM(
                    //                                    order_details.quantity
                    //                                )'),
                ]
            )
            ->withSum('orderDetails as items_count', 'quantity')
            ->when(! $request_has_order_processed_filter, function (Builder $query) {
                $un_processed_order_statuses = [
                    OrderStatus::Pending->value,
                    OrderStatus::OnTheWay->value,
                    OrderStatus::DriverAccept->value,
                    OrderStatus::Accepted->value,
                ];

                $query->whereIn('status', $un_processed_order_statuses);
            })
            ->when($request_has_order_processed_filter, function (Builder $query) {
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
    #[OAT\Post(path: '/user/orders', tags: ['userOrders'])]
    #[JsonRequestBody(CreateOrderData::class)]
    #[SuccessItemResponse('boolean')]
    public function store(
        CreateOrderData $request_create_order_data,
    ) {

        Log::info('Accessing User OrderController store method');

        /** @var string $applied_coupon_id */
        $applied_coupon_id =
            Coupon::query()
                ->firstWhere('code', $request_create_order_data->code)
                ->id;

        /** @var Collection<int, string> $request_order_details_unique_product_ids */
        $request_order_details_unique_product_ids =
            $request_create_order_data
                ->order_details
                ->pluck('product_id')
                ->unique();

        /** @var EloquentCollection<int, Product> $order_products */
        $order_products =
            Product::query()
                ->whereIdIn(
                    $request_order_details_unique_product_ids
                )
                ->get();

        $request_order_details =
            $request_create_order_data
                ->order_details;

        $order_details =
            $request_order_details
                ->groupBy('product_id')
                ->map(
                    function (
                        Collection $product_grouping,
                        string $key
                    ) use ($order_products) {
                        /** @var Collection<int, Product> $product_grouping */
                        $product =
                            $order_products
                                ->firstWhere('id', $key);

                        return new OrderDetails([
                            'product_id' => $key,
                            'unit_price' => $product->price,
                            'unit_price_offer' => $product->price_offer,
                            'quantity' => $product_grouping->sum('quantity'),
                        ]);
                    }
                );

        /** @var float $order_total */
        $order_total =
            $order_details
                ->sum(function (OrderDetails $item) {

                    $price = $item->unit_price ?? $item->unit_price_offer;

                    return $price * $item->quantity;
                });

        /** @var int $autheticated_user_id */
        $autheticated_user_id = auth()->id();

        $order = Order::query()
            ->create([
                'user_id' => $autheticated_user_id,
                'coupon_id' => $applied_coupon_id,
                'notice' => $request_create_order_data->notice,
                'required_time' => $request_create_order_data->required_time,
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
