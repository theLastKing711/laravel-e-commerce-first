<?php

namespace App\Data\Admin\Category;

use App\Data\Shared\Swagger\Property\DateProperty;
use App\Transformers\ToWebStoragePathTransformer;
use OpenApi\Attributes as OAT;
use Spatie\LaravelData\Attributes\WithTransformer;
use Spatie\LaravelData\Data;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
#[Oat\Schema()]
class CategoryData extends Data
{
    public function __construct(
        #[OAT\Property()]
        public int $id,
        #[OAT\Property()]
        public ?int $parent_id,
        #[OAT\Property()]
        public ?string $parent_name,
        #[OAT\Property()]
        public string $name,
        #[
            OAT\Property(),
            WithTransformer(ToWebStoragePathTransformer::class, folder: 'category')
        ]
        public ?string $image,
        #[DateProperty]
        public string $created_at,
//        #[OAT\Property(default: 'type of containing type')]
//        public ?CategoryData $parent,
    ) {
    }
}
