<?php

namespace App\Data\Admin\Product\PathParameters;

use Spatie\LaravelData\Data;
use OpenApi\Attributes as OAT;
use Spatie\LaravelData\Attributes\FromRouteParameter;


class ProductIdPathParameterData extends Data
{
    public function __construct(
        #[
            OAT\PathParameter(
                parameter: 'adminProductIdPathParameter', //the name used in ref
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

    //    public static function rules(ValidationContext $context): array
    //    {
    //        return [
    //            'id' => 'required|int|exists:products,id',
    //        ];
    //    }
}
