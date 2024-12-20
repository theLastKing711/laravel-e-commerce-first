<?php

namespace App\Data\Admin\User\Product\Details\QueryParameters;

use OpenApi\Attributes as OAT;
use Spatie\LaravelData\Dto;

#[OAT\Schema()]
class GetProductDetailsQueryParameterData extends Dto
{
    public function __construct(
        #[OAT\Property]
        public ?string $first_variant_value_id,
        #[OAT\Property]
        public ?string $second_variant_value_id,
        #[OAT\Property]
        public ?string $third_variant_value_id,
    ) {

    }
}
