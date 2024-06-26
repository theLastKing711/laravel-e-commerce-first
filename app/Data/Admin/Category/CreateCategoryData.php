<?php

namespace App\Data\Admin\Category;

use App\Models\Category;
use App\Models\Product;


use Illuminate\Http\UploadedFile;
use Illuminate\Validation\Rule;
use OpenApi\Attributes as OAT;
use Spatie\LaravelData\Attributes\Validation\File;
use Spatie\LaravelData\Attributes\Validation\Required;
use Spatie\LaravelData\Attributes\Validation\StringType;
use Spatie\LaravelData\Data;

#[Oat\Schema(schema: 'adminCreateCategory')]
class CreateCategoryData extends Data
{
    public function __construct(
        #[
            OAT\Property(type: 'string'),
            Required,
            StringType
        ]
        public string $name,
        #[
            OAT\Property(type: 'string', format: 'binary'),
            File
        ]
        public ?UploadedFile $image,
    ) {
    }

}


