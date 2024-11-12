<?php

namespace App\Data\Admin\Order\PathParameters;

use App\Enum\OrderStatus;
use OpenApi\Attributes as OAT;
use Spatie\LaravelData\Attributes\FromRouteParameter;
use Spatie\LaravelData\Data;

class OrderIdPathParameterData extends Data
{
    public function __construct(
        #[
            OAT\PathParameter(
                parameter: 'adminOrderIdPathParameter', //the name used in ref
                name: 'id',
                schema: new OAT\Schema(
                    type: 'integer',
                ),
            ),
            FromRouteParameter('id'),
        ]
        public int $id,
        #[OAT\Property]
        public OrderStatus $order_status,
    ) {
    }
}
