<?php

namespace App\Data\Admin\User\Product\Details;

use OpenApi\Attributes as OAT;
use Spatie\LaravelData\Attributes\FromRouteParameter;
use Spatie\LaravelData\Data;

class GetProductDetailsRequestData extends Data
{
    public function __construct(
        #[
            OAT\PathParameter(
                parameter: 'userProductVariationtIdPathParameter', //the name used in ref
                name: 'id',
                schema: new OAT\Schema(
                    type: 'string',
                ),
            ),
            FromRouteParameter('id'),
        ]
        public string $id,
        #[OAT\Property]
        public ?GetProductDetailsQueryParameterData $variant_value_query_paramters,
    ) {

    }
}
