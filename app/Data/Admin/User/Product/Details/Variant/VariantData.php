<?php

namespace App\Data\Admin\User\Product\Details\Variant;

use App\Data\Admin\User\Product\Details\Variant\VariantValue\VariantValueData;
use App\Data\Shared\Swagger\Property\ArrayProperty;
use App\Models\Variant;
use Illuminate\Support\Collection;
use OpenApi\Attributes as OAT;
use Spatie\LaravelData\Data;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript()]
#[Oat\Schema(schema: 'variantData')]
class VariantData extends Data
{
    /** @param Collection<int, VariantValueData> $variant_values */
    public function __construct(
        #[OAT\Property]
        public string $id,
        #[OAT\Property]
        public string $name,
        #[ArrayProperty(VariantValueData::class)]
        public Collection $variant_values,
    ) {
    }

    public static function fromModel(Variant $variant): self
    {

        return new self(
            id: $variant->id,
            name: $variant->name,
            variant_values: VariantValueData::collect($variant->variantValues, Collection::class)
        );
    }
}
