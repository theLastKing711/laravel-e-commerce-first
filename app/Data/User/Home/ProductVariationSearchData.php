<?php

namespace App\Data\User\Home;

use App\Data\Shared\Media\SingleMedia;
use OpenApi\Attributes as OAT;
use Spatie\LaravelData\Data;

#[Oat\Schema()]
class ProductVariationSearchData extends Data
{
    public function __construct(
        #[OAT\Property]
        public string $id,
        // #[OAT\Property]
        // public string $name,
        #[OAT\Property]
        public int $available,
        #[OAT\Property]
        public string $price,
        #[OAT\Property]
        public ?SingleMedia $image,
    ) {
    }
}
