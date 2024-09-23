<?php

namespace App\Data\Admin\Category;

use App\Data\Shared\Swagger\Property\ArrayProperty;
use App\Models\Category;
use Illuminate\Support\Collection;
use Illuminate\Validation\Rule;
use OpenApi\Attributes as OAT;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Support\Validation\ValidationContext;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
#[Oat\Schema()]
class ParentCategoryIdsData extends Data
{
    public function __construct(
        #[ArrayProperty]
        /** @var Collection<int, int> */
        public array $ids,
    ) {
    }

    public static function rules(ValidationContext $context): array
    {

        return [
            'ids' => [
                'array',
                'required',
            ],
            'ids.*' => [
                'int',
                Rule::exists(Category::class, 'id')
                    ->where('parent_id', null),
            ],
        ];
    }

    public static function messages(): array
    {
        return [
            'ids.*.exists' => [
                'Please select a valid category id.',
            ],
        ];
    }
}
