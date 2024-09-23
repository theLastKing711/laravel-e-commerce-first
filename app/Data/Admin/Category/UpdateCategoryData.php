<?php

namespace App\Data\Admin\Category;

use App\Data\Shared\Swagger\Property\FileProperty;
use Illuminate\Http\UploadedFile;
use OpenApi\Attributes as OAT;
use Spatie\LaravelData\Data;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
#[Oat\Schema()]
class UpdateCategoryData extends Data
{
    public function __construct(
        #[OAT\Property(type: 'string')]
        public string $name,
        #[OAT\Property()]
        public int $parent_id,
        #[FileProperty()]
        public ?UploadedFile $image,
    ) {
    }
}
