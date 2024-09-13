<?php

namespace App\Data\Admin\Product\QueryParameters;

use OpenApi\Attributes as OAT;
use Spatie\LaravelData\Data;

//#[Oat\Schema(schema: 'adminProductNameQueryData')]
class ProductNameQueryParameterData extends Data
{
    public function __construct(
        #[OAT\QueryParameter(
            parameter: 'adminProductName', //the name used in ref
            name: 'name', // the name used in swagger, becomes the ref in case the parameter is missing
            required: false,
            schema: new OAT\Schema(
                type: 'string'
            )
        )]
        public ?string $name,
    ) {
    }
}
