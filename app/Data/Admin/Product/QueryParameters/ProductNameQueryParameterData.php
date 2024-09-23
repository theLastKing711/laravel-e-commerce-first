<?php

namespace App\Data\Admin\Product\QueryParameters;

use OpenApi\Attributes as OAT;
use Spatie\LaravelData\Data;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
class ProductNameQueryParameterData extends Data
{
    public function __construct(
        public ?string $name,
    ) {
    }
}
