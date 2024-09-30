<?php

namespace App\Data\Admin\Category;

use App\Data\Shared\File\CreateFilePathData;
use App\Data\Shared\Swagger\Property\ArrayProperty;
use App\Data\Shared\Swagger\Property\FileProperty;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;
use OpenApi\Attributes as OAT;
use Spatie\LaravelData\Attributes\Validation\Exists;
use Spatie\LaravelData\Data;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
#[Oat\Schema()]
class CreateCategoryData extends Data
{
    public function __construct(
        #[OAT\Property()]
        public string $name,
        #[
            OAT\Property(),
            Exists('categories', 'id')
        ]
        public ?int $parent_id,
        #[ArrayProperty(CreateFilePathData::class)]
        /** @var Collection<int, CreateFilePathData> */
        public Collection $image_urls,
    ) {
    }
}
