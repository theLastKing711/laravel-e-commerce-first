<?php

namespace App\Data\Admin\Category\PathParameters;

use App\Models\Category;
use OpenApi\Attributes as OAT;
use Spatie\LaravelData\Attributes\FromRouteParameter;
use Spatie\LaravelData\Attributes\Validation\Exists;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Support\Validation\ValidationContext;

#[Oat\Schema(schema: 'adminCategoryPathClass')]
class CategoryIdPathParameterData extends Data
{
    public function __construct(
        #[
            OAT\PathParameter(
                parameter: 'adminCategoryIdPathParameter', //the name used in ref
                name: 'id',
                schema: new OAT\Schema(
                    type: 'integer',
                ),
            ),
            FromRouteParameter('id'),
            Exists(Category::class)
        ]
        public int $id,
    ) {
    }

//    public static function rules(ValidationContext $context): array
//    {
//        return [
//            'id' => 'required|int|exists:categories,id',
//        ];
//    }
}
