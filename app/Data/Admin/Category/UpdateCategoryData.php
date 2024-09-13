<?php

namespace App\Data\Admin\Category;

use Illuminate\Http\UploadedFile;
use OpenApi\Attributes as OAT;
use Spatie\LaravelData\Data;

#[Oat\Schema(schema: 'adminUpdateCategory')]
class UpdateCategoryData extends Data
{
    public function __construct(
        #[
            OAT\Property(type: 'string'),
        ]
        public string $name,
        #[
            OAT\Property(type: 'string', format: 'binary'),
        ]
        public ?UploadedFile $image,
    ) {
    }
}
