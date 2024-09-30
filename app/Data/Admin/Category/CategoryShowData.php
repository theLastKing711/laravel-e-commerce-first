<?php

namespace App\Data\Admin\Category;

use App\Data\Shared\File\ShowFileData;
use App\Data\Shared\Swagger\Property\ArrayProperty;
use App\Data\Shared\Swagger\Property\DateProperty;
use App\Models\Category;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use OpenApi\Attributes as OAT;
use Spatie\LaravelData\Data;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
#[Oat\Schema()]
class CategoryShowData extends Data
{
    public function __construct(
        #[OAT\Property()]
        public int $id,
        #[OAT\Property()]
        public ?int $parent_id,
        #[OAT\Property()]
        public string $name,
        #[ArrayProperty]
        /** @var Collection <int, ShowFileData> */
        public ?Collection $images,
        #[DateProperty]
        public string $created_at,
        //        #[OAT\Property(default: 'type of containing type')]
        //        public ?CategoryData $parent,
    ) {
    }

    public static function fromModel(Category $category): self
    {

        $category_images = $category->fetchAllMedia();

        Log::info('category images {category_images}', ['category_images' => $category_images]);

        return new self(
            id: $category->id,
            parent_id: $category->parent_id,
            name: $category->name,
            images: ShowFileData::collect($category_images),
            created_at: $category->created_at,
        );
    }
}
