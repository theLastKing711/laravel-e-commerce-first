<?php

namespace App\Data\Admin\Order\QueryParameters;

use App\Enum\OrderStatus;
use OpenApi\Attributes as OAT;
use Spatie\LaravelData\Data;

class OrderStatusQueryParameterData extends Data
{
    public function __construct(
        #[OAT\QueryParameter(
            parameter: 'adminOrderStatus', //the name used in ref
            name: 'order_status',// the name used in swagger ui, becomes the ref in case the parameter is missing
            schema: new OAT\Schema()
        )]
        public ?OrderStatus $order_status,
    ) {
    }
}
