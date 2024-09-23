<?php

namespace App\Data\Admin\Product;

use App\Data\Admin\Category\CategoryData;
use App\Data\Shared\Swagger\Property\ArrayProperty;
use App\Data\Shared\Swagger\Property\DateProperty;
use App\Enum\Unit;
use App\Models\Product;
use App\Transformers\ToWebStoragePathTransformer;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use OpenApi\Attributes as OAT;
use Spatie\LaravelData\Attributes\WithTransformer;
use Spatie\LaravelData\Data;

#[Oat\Schema(schema: 'adminProduct')]
class ProductData extends Data
{
    public function __construct(
        #[OAT\Property()]
        public int $id,
        #[OAT\Property()]
        public string $name,
        #[OAT\Property()]
        public string $price,
        #[OAT\Property()]
        public string $description,
        #[OAT\Property()]
        public ?string $price_offer,
        #[OAT\Property()]
        public bool $is_most_buy,
        #[OAT\Property()]
        public bool $is_active,
        #[OAT\Property()]
        public ?Unit $unit,
        #[OAT\Property()]
        public ?int $unit_value,
        #[
            OAT\Property(),
            WithTransformer(ToWebStoragePathTransformer::class, folder: 'product')
        ]
        public ?string $image,
        #[DateProperty]
        public string $created_at,
        #[ArrayProperty(CategoryData::class)]
        /** @var Collection<int, CategoryData> */
        public Collection $childCategories,
        #[ArrayProperty(CategoryData::class)]
        /** @var Collection<int, CategoryData> */
        public Collection $parentCategories
    ) {
    }

    public static function fromModel(Product $product): self
    {

        Log::info('accessing after pagination');

        $productCategories = $product->categories;

        return new self(
            id: $product->id,
            name: $product->name,
            price: $product->price,
            description: $product->description,
            price_offer: $product->price_offer,
            is_most_buy: $product->is_most_buy,
            is_active: $product->is_active,
            unit: $product->unit,
            unit_value: $product->unit_value,
            image: $product->image,
            created_at: $product->created_at,
            childCategories: CategoryData::collect($productCategories),
            parentCategories: CategoryData::collect(
                $product->categories->pluck('parent')->unique('id')
            ),
        );
    }
}
