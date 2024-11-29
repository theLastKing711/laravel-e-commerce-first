<?php

namespace App\Data\Admin\User\Product\Details;

use App\Data\Admin\User\Product\Details\QueryParameters\GetProductDetailsQueryParameterData;
use OpenApi\Attributes as OAT;
use Spatie\LaravelData\Attributes\FromRouteParameter;
use Spatie\LaravelData\Data;

#[OAT\Schema()]
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
        public ?string $first_variant_value_id,
        #[OAT\Property]
        public ?string $second_variant_value_id,
        #[OAT\Property]
        public ?string $third_variant_value_id,
        // #[OAT\Property]
        // public ?GetProductDetailsQueryParameterData $variant_value_ids_query_paramters,
    ) {

    }
}
