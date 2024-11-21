<?php

namespace App\Data\Admin\Admin\PathParameters;

use OpenApi\Attributes as OAT;
use Spatie\LaravelData\Attributes\FromRouteParameter;
use Spatie\LaravelData\Data;

#[Oat\Schema()]
class AdminProductIdPathParameterData extends Data
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
        public string $id,
    ) {
    }
}
