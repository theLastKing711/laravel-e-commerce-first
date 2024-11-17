<?php

namespace App\Data\Admin\Product\PathParameters;

use OpenApi\Attributes as OAT;
use Spatie\LaravelData\Attributes\FromRouteParameter;
use Spatie\LaravelData\Data;

class ProductIdPathParameterData extends Data
{
    public function __construct(
        #[
            OAT\PathParameter(
                parameter: 'adminProductIdPathParameter', //the name used in ref
                name: 'id',
                schema: new OAT\Schema(
                    type: 'string',
                ),
            ),
            FromRouteParameter('id'),
        ]
        public int $id,
    ) {
    }

    //    public static function rules(ValidationContext $context): array
    //    {
    //        return [
    //            'id' => 'required|int|exists:products,id',
    //        ];
    //    }
}
