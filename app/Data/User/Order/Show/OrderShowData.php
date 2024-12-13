<?php

namespace App\Data\User\Order\Show;

use App\Data\Shared\Media\SingleMedia;
use App\Data\Shared\Swagger\Property\ArrayProperty;
use App\Data\Shared\Swagger\Property\DateProperty;
use App\Models\Order;
use App\Models\OrderDetails;
use Illuminate\Support\Collection;
use OpenApi\Attributes as OAT;
use Spatie\LaravelData\Data;

#[Oat\Schema()]
class OrderShowData extends Data
{
    /** @param Collection<int, OrderShowItemData> $items*/
    public function __construct(
        #[OAT\Property()]
        public int $id,
        #[DateProperty]
        public string $required_time,
        #[OAT\Property()]
        public string $receiver_name,
        #[OAT\Property()]
        public float $total,
        #[OAT\Property()]
        public float $discount,
        #[OAT\Property()]
        public float $delivery_price,
        #[ArrayProperty(OrderShowItemData::class)]
        public Collection $items,
        #[DateProperty]
        public string $created_at,
        #[OAT\Property()]
        public ?string $driver_lat = '0',
        #[OAT\Property()]
        public ?string $driver_lon = '0',
    ) {}

    public static function fromModel(Order $order): self
    {
        $order_total_with_shipment_and_delivery = $order->getTotalWithShipmentAndDiscount();

        $coupon_discount_value = $order->coupon?->value ?? 0;

        $driver_lat = $order->driver?->lat;

        $driver_lon = $order->driver?->lon;

        $order_details = $order->orderDetails;

        /** @var Collection<int, OrderShowItemData> $order_items */
        $order_items = $order_details
            ->map(function (OrderDetails $orderItem): OrderShowItemData {

                $product = $orderItem->product;

                $product_has_three_variants =
                (bool) $orderItem->second_variant_combination_id;

                if ($product_has_three_variants) {

                    $product_second_variant_combination =
                        $orderItem
                            ->secondVariantCombination;

                    return new OrderShowItemData(
                        product_id: $orderItem->id,
                        product_variation_id: $product_second_variant_combination
                            ->id,
                        quantity: $orderItem->quantity,
                        price: $product->price ?? $product->price_offer,
                        name: $product->name,
                        image: SingleMedia::from($product_second_variant_combination)
                    );
                }

                $product_has_two_variants =
                (bool) $orderItem
                    ->variant_combination_id;

                if ($product_has_two_variants) {

                    $product_variant_combination =
                        $orderItem
                            ->variantCombination;

                    return new OrderShowItemData(
                        product_id: $orderItem->id,
                        product_variation_id: $product_variant_combination
                            ->id,
                        quantity: $orderItem->quantity,
                        price: $product->price ?? $product->price_offer,
                        name: $product->name,
                        image: SingleMedia::from($product_variant_combination)
                    );
                }

                $product_has_one_variant =
                (bool) $orderItem
                    ->variant_value_id;

                if ($product_has_one_variant) {

                    $product_variant_value =
                        $orderItem
                            ->variantValue;

                    return new OrderShowItemData(
                        product_id: $orderItem->id,
                        product_variation_id: $product_variant_value
                            ->id,
                        quantity: $orderItem->quantity,
                        price: $product->price ?? $product->price_offer,
                        name: $product->name,
                        image: SingleMedia::from($product_variant_value)
                    );
                }

                $product_iamge =
                    $product->medially->count()
                    > 0
                    ?
                    SingleMedia::from($product)
                    :
                    null;

                return new OrderShowItemData(
                    product_id: $orderItem->id,
                    product_variation_id: null,
                    quantity: $orderItem->quantity,
                    price: $product->price ?? $product->price_offer,
                    name: $product->name,
                    image: $product_iamge
                );
            });

        return new self(
            id: $order->id,
            required_time: $order->required_time,
            receiver_name: $order->user->name,
            total: $order_total_with_shipment_and_delivery,
            discount: $coupon_discount_value,
            delivery_price: $order->delivery_price,
            items: $order_items,
            created_at: $order->created_at,
            driver_lon: $driver_lon,
            driver_lat: $driver_lat,
        );
    }
}
