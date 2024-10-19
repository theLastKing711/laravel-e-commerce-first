<?php

namespace App\Data\User\Home;

use OpenApi\Attributes as OAT;
use Spatie\LaravelData\Data;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
#[Oat\Schema()]
class HomeProductListData extends Data
{
    public function __construct(
        #[OAT\Property()]
        public int $id,
        #[OAT\Property()]
        public string $name,
        #[OAT\Property()]
        public string $price,
        #[OAT\Property()]
        public ?bool $is_favourite,
        #[OAT\Property()]
        public ?string $image_url,
    ) {
    }
}
