<?php

namespace App\Data\Admin\Order\QueryParameters;

use OpenApi\Attributes as OAT;
use Spatie\LaravelData\Data;

class OrderSearchQueryParameterData extends Data
{
    public function __construct(
        #[OAT\QueryParameter(
            parameter: 'adminOrderSearch', //the name used in ref
            name: 'search', // the name used in swagger ui, becomes the ref in case the parameter is missing
            schema: new OAT\Schema('string')
        )]
        public ?string $search,
    ) {
    }
}
