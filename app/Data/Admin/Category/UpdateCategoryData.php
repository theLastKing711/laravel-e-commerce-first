<?php

namespace App\Data\Admin\Category;

use App\Data\Shared\File\UpdateFileData;
use App\Data\Shared\Swagger\Property\ArrayProperty;
use Illuminate\Support\Collection;
use OpenApi\Attributes as OAT;
use Spatie\LaravelData\Attributes\Validation\Exists;
use Spatie\LaravelData\Data;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
#[Oat\Schema()]
class UpdateCategoryData extends Data
{
    public function __construct(
        #[OAT\Property()]
        public string $name,
        #[
            OAT\Property(),
            Exists('categories', 'id')
        ]
        public ?int $parent_id,
        #[ArrayProperty(UpdateFileData::class)]
        /** @var Collection<int, UpdateFileData> */
        public Collection $image_urls,
    ) {
    }
}
