<?php

namespace App\Data\Admin\Product;

use App\Data\Admin\Category\CategoryData;
use App\Enum\Unit;
use App\Models\Product;
use App\Transformers\ToWebStoragePathTransformer;
use Illuminate\Support\Collection;
use OpenApi\Attributes as OAT;
use Spatie\LaravelData\Attributes\WithTransformer;
use Spatie\LaravelData\Data;

#[Oat\Schema(schema: 'adminProduct')]
class ProductData extends Data
{
    public function __construct(
        #[OAT\Property(type: 'integer')]
        public int $id,
        #[OAT\Property(type: 'string')]
        public string $name,
        #[
            OAT\Property(type: 'string'),
        ]
        public string $price,
        #[
            OAT\Property(type: 'string'),
        ]
        public string $description,
        #[
            OAT\Property(type: 'string'),
        ]
        public ?string $price_offer,
        #[
            OAT\Property(type: 'boolean'),
        ]
        public bool $is_most_buy,
        #[
            OAT\Property(type: 'boolean'),
        ]
        public bool $is_active,
        #[OAT\Property()]
        public ?Unit $unit,
        #[OAT\Property(type: 'integer')]
        public ?int $unit_value,
        #[
            OAT\Property(type: 'string'),
            WithTransformer(ToWebStoragePathTransformer::class, folder: 'product')
        ]
        public ?string $image,
        #[OAT\Property(
            type: 'string',
            format: 'datetime',
            default: '2017-02-02 18:31:45',
            pattern: 'YYYY-MM-DD'
        )]
        public string $created_at,
        #[OAT\Property(
            type: 'array',
            items: new OAT\Items(
                type: CategoryData::class,
            )
        )]
        /** @var Collection<int, CategoryData> */
        public Collection $childCategories,
        #[OAT\Property(
            type: 'array',
            items: new OAT\Items(
                type: CategoryData::class,
            )
        )]
        /** @var Collection<int, CategoryData> */
        public Collection $parentCategories
    ) {
    }

    public static function fromModel(Product $product): self
    {
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
