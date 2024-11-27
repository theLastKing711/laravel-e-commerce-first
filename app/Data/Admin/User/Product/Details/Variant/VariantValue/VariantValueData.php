<?php

namespace App\Data\Admin\User\Product\Details\Variant\VariantValueData;

use App\Data\Admin\User\Product\Details\GetProductDetailsQueryParameterData;
use App\Data\Shared\Media\SingleMedia;
use OpenApi\Attributes as OAT;
use Spatie\LaravelData\Data;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript()]
#[Oat\Schema()]
class VariantValueData extends Data
{
    public function __construct(
        #[OAT\Property]
        public string $id,
        #[OAT\Property]
        public string $name,
        #[OAT\Property]
        public bool $is_selected,
        #[OAT\Property]
        public bool $is_not_available,
        #[OAT\Property]
        public SingleMedia $image,
        #[OAT\Property]
        public GetProductDetailsQueryParameterData $combinations_ids_with_selected_variant_value,
    ) {
    }
}
