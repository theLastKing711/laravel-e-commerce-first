<?php

namespace App\Data\Admin\User\Product\Variant\VariantValue;

use App\Data\Shared\Media\SingleMedia;
use App\Models\VariantValue;
use OpenApi\Attributes as OAT;
use Spatie\LaravelData\Data;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript()]
#[Oat\Schema()]
class VariantValueData extends Data
{
    public function __construct(
        #[OAT\Property]
        public int $id,
        #[OAT\Property]
        public string $name,
        #[OAT\Property]
        public string $price,
        #[OAT\Property]
        public int $available,
        #[OAT\Property]
        public ?SingleMedia $thumbnail,
    ) {
    }

    public static function fromModel(VariantValue $variant_value): self
    {
        return new self(
            id: $variant_value->id,
            name: $variant_value->name,
            price: $variant_value->price,
            available: $variant_value->available,
            thumbnail: SingleMedia::from($variant_value)
        );
    }
}
