<?php

namespace App\Data\Admin\User\Product;

use OpenApi\Attributes as OAT;
use Spatie\LaravelData\Attributes\FromRouteParameter;
use Spatie\LaravelData\Attributes\Validation\Exists;
use Spatie\LaravelData\Data;

#[Oat\Schema()]
class ProductIdData extends Data
{
    public function __construct(
        #[
            OAT\Property(),
            Exists('products'),
            OAT\PathParameter(
                parameter: 'userProductIdPathParameter', //the name used in ref
                name: 'id',
                schema: new OAT\Schema(
                    type: 'integer',
                ),
            ),
            FromRouteParameter('id'),
        ]
        public int $id,
    ) {
    }
}
