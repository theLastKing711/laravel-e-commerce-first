<?php

namespace App\Http\Controllers\User;

use App\Data\Shared\Swagger\Property\QueryParameter;
use App\Data\Shared\Swagger\Request\JsonRequestBody;
use App\Data\Shared\Swagger\Response\SuccessItemResponse;
use App\Data\Shared\Swagger\Response\SuccessListResponse;
use App\Data\User\Order\Create\CreateOrderData;
use App\Data\User\Order\Create\CreateOrderDetailsData;
use App\Data\User\Order\Index\OrderData;
use App\Data\User\Order\QueryParameters\OrderProcessedQueryParameterData;
use App\Data\User\Order\Show\OrderShowData;
use App\Enum\OrderStatus;
use App\Http\Controllers\Controller;
use App\Models\Coupon;
use App\Models\Order;
use App\Models\OrderDetails;
use App\Models\Product;
use App\Models\SecondVariantCombination;
use App\Models\User;
use App\Models\VariantCombination;
use App\Models\VariantValue;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
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
            ->with([
                'coupon',
                'orderDetails' => [
                    'product' => [
                        'medially',
                    ],
                    'variantValue' => [
                        'medially',
                    ],
                    'variantCombination' => [
                        'medially',
                    ],
                    'secondVariantCombination' => [
                        'medially',
                    ],
                ],
                'driver',
                'user',
            ])
            ->firstWhereId($order->id);

        return OrderShowData::from($order);
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

        /** @var User $authenticatedUser */
        $authenticatedUser = auth()->user();

        $request_has_order_processed_filter = $query_param->is_order_processed;

        $orders = Order::query()
            ->select(['id', 'status as order_status', 'required_time', 'created_at'])
            ->whereUserId(30)
            ->addSelect(
                [
                    'total' => OrderDetails::query()
                        ->whereColumn('order_id', 'orders.id')
                        ->join('products', 'order_details.product_id', 'products.id')
                        ->leftJoin('coupons', 'orders.coupon_id', 'coupons.id')
                        ->leftJoin('variant_values', 'order_details.variant_value_id', 'variant_values.id')
                        ->leftJoin('variant_combination', 'order_details.variant_combination_id', 'variant_combination.id')
                        ->leftJoin('second_variant_combination', 'order_details.second_variant_combination_id', 'second_variant_combination.id')
                        ->selectRaw('
                            SUM(
                                COALESCE
                                (
                                    second_variant_combination.price,
                                    variant_combination.price,
                                    variant_values.price,
                                    products.price_offer,
                                    order_details.unit_price
                                )
                                *
                                order_details.quantity
                            )
                            +
                            orders.delivery_price
                            -
                            COALESCE(
                                coupons.value,
                                0
                            )
                        '),
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

        /** @var int $applied_coupon_id */
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
                ->withCount('variants')
                ->get();

        $request_order_details =
            $request_create_order_data
                ->order_details;

        /** @var Collection<int, string> $reqeust_product_variation_ids */
        $request_product_variation_ids =
            $request_create_order_data
                ->order_details
                ->pluck('product_variation_id');

        /** @var Collection<int, VariantValue> $one_variant_products */
        $one_variant_products =
            VariantValue::query()
                ->whereIdIn($request_product_variation_ids)
                ->get();

        /** @var Collection<int, VariantCombination> $two_variant_products */
        $two_variant_products =
            VariantCombination::query()
                ->whereIdIn($request_product_variation_ids)
                ->get();

        /** @var Collection<int, SecondVariantCombination> $three_variant_products */
        $three_variant_products =
            SecondVariantCombination::query()
                ->whereIdIn($request_product_variation_ids)
                ->get();

        /** @var Collection<int, OrderDetails> $order_details */
        $order_details =
            $request_order_details
                ->groupBy('product_variation_id')
                ->map(
                    function (
                        Collection $product_grouping,
                        string $key
                    ) use ($order_products, $one_variant_products, $two_variant_products, $three_variant_products): OrderDetails {
                        /** @var Collection<int, CreateOrderDetailsData> $product_grouping */

                        /** @var CreateOrderDetailsData $request_order_detail */
                        $request_order_detail =
                            $product_grouping
                                ->first();

                        /** @var Product $product */
                        $product =
                            $order_products
                                ->firstWhereId($request_order_detail->product_id);

                        $product_variants_count = $product->variants_count;

                        $product_has_no_variation = $product_variants_count == 0;

                        if ($product_has_no_variation) {

                            return new OrderDetails([
                                'product_id' => $product->id,
                                'unit_price' => $product->price,
                                'unit_price_offer' => $product->price_offer,
                                'quantity' => $product_grouping->sum('quantity'),
                            ]);
                        }

                        $product_has_one_variant = $product_variants_count == 1;

                        if ($product_has_one_variant) {

                            /** @var VariantValue $product_variant_value */
                            $product_variant_value =
                                $one_variant_products
                                    ->firstWhereId($key);

                            return new OrderDetails([
                                'product_id' => $product->id,
                                'variant_value_id' => $product_variant_value->id,
                                'unit_price' => $product_variant_value->price,
                                'unit_price_offer' => null,
                                'quantity' => $product_grouping->sum('quantity'),
                            ]);
                        }

                        $product_has_two_variants = $product_variants_count == 2;

                        if ($product_has_two_variants) {

                            /** @var VariantCombination $product_variant_combination */
                            $product_variant_combination =
                                $two_variant_products
                                    ->firstWhereId($key);

                            return new OrderDetails([
                                'product_id' => $product->id,
                                'variant_combination_id' => $product_variant_combination->id,
                                'unit_price' => $product_variant_combination->price,
                                'unit_price_offer' => null,
                                'quantity' => $product_grouping->sum('quantity'),
                            ]);
                        }

                        $product_has_three_variants = $product_variants_count == 3;

                        if ($product_has_three_variants) {

                            /** @var SecondVariantCombination $product_second_variant_combination */
                            $product_second_variant_combination =
                                $three_variant_products
                                    ->firstWhereId($key);

                            return new OrderDetails([
                                'product_id' => $product->id,
                                'second_variant_combination_id' => $product_second_variant_combination->id,
                                'unit_price' => $product_second_variant_combination->price,
                                'unit_price_offer' => null,
                                'quantity' => $product_grouping->sum('quantity'),
                            ]);
                        }
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

        DB::transaction(function () use ($applied_coupon_id, $request_create_order_data, $order_total, $order_details) {

            $order = Order::query()
                ->create([
                    'user_id' => 30,
                    'coupon_id' => $applied_coupon_id,
                    'notice' => $request_create_order_data->notice,
                    'required_time' => $request_create_order_data->required_time,
                    'total' => $order_total,
                    'status' => OrderStatus::Pending,
                    'lat' => 0,
                    'lon' => 0,
                    'delivery_price' => 200,
                ]);

            $order
                ->orderDetails()
                ->saveMany($order_details);

        });

        return true;

    }
}
