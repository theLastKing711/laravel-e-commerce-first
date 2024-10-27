<?php

namespace App\Data\Admin\User\Product;

use OpenApi\Attributes as OAT;
use Spatie\LaravelData\Data;

#[Oat\Schema()]
class FavouriteProductData extends Data
{
    public function __construct(
        #[OAT\Property]
        public int $id,
        #[OAT\Property]
        public string $name,
        #[OAT\Property]
        public string $price,
        #[OAT\Property]
        public bool $is_favourite,
        #[OAT\Property]
        public ?string $image_url,
    ) {
    }
}
