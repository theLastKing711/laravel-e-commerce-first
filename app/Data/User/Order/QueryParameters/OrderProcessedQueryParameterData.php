<?php

namespace App\Data\User\Order\QueryParameters;

use OpenApi\Attributes as OAT;
use Spatie\LaravelData\Data;


class OrderProcessedQueryParameterData extends Data
{
    public function __construct(
        #[OAT\QueryParameter(
            parameter: 'userOrderProcessed', //the name used in ref
            name: 'is_order_processed', // the name used in swagger ui, becomes the ref in case the parameter is missing
            schema: new OAT\Schema()
        )]
        public bool $is_order_processed,
    ) {
    }
}
