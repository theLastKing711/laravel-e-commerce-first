<?php

namespace App\Data\Admin\Category;

use Illuminate\Http\UploadedFile;
use OpenApi\Attributes as OAT;
use Spatie\LaravelData\Attributes\Validation\Exists;
use Spatie\LaravelData\Attributes\WithTransformer;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Transformers\DateTimeInterfaceTransformer;

#[Oat\Schema(schema: 'adminCreateCategory')]
class CreateCategoryData extends Data
{
    public function __construct(
        #[
            OAT\Property(type: 'string'),
        ]
        public string $name,
        #[
            OAT\Property(type: 'integer'),
            Exists('categories', 'id')
        ]
        public ?int $parent_id,
        #[
            OAT\Property(type: 'string', format: 'binary'),
        ]
        public ?UploadedFile $image,
    ) {
    }
}
