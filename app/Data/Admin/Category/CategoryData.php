<?php

namespace App\Data\Admin\Category;

use App\Services\FileService;
use App\Transformers\ToWebStoragePathTransformer;
use OpenApi\Attributes as OAT;
use Spatie\LaravelData\Attributes\WithTransformer;
use Spatie\LaravelData\Data;

#[Oat\Schema(schema: 'adminCategory')]
class CategoryData extends Data
{
    public function __construct(
        #[OAT\Property(type: 'integer')]
        public int $id,
        #[OAT\Property(type: 'integer')]
        public ?int $parent_id,
        #[OAT\Property(type: 'string')]
        public string $name,
        #[
            OAT\Property(type: 'string'),
            WithTransformer(ToWebStoragePathTransformer::class, folder: 'category')
        ]
        public ?string $image,
        #[OAT\Property(
            type: 'string',
            format: 'datetime',
            default: '2017-02-02 18:31:45',
            pattern: 'YYYY-MM-DD'
        )]
        public string $created_at,
        #[OAT\Property(type: CategoryData::class)]
        public ?CategoryData $parent,
    ) {
    }

}
