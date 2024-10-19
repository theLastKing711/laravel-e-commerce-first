<?php

namespace App\Data\User\Category\Index;

use App\Models\Category;
use Illuminate\Support\Facades\Log;
use OpenApi\Attributes as OAT;
use Spatie\LaravelData\Data;

#[OAT\Schema()]
class ParentListData extends Data
{
    public function __construct(
        #[OAT\Property()]
        public int $id,
        #[OAT\Property()]
        public ?int $parent_id,
        #[OAT\Property()]
        public string $name,
        #[OAT\Property()]
        public ?string $image_url,
    ) {
    }

    public static function fromModel(Category $category): self
    {
        Log::info('category {category}', ['category' => $category]);

        $first_category_image = $category
            ->medially
            ->first()
            ?->file_url;

        return new self(
            id: $category->id,
            parent_id: $category->parent_id,
            name: $category->name,
            image_url: $first_category_image
        );
    }
}
