<?php

namespace App\Data\Admin\Category;

use App\Models\Category;
use Illuminate\Support\Collection;
use Illuminate\Validation\Rule;
use OpenApi\Attributes as OAT;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Support\Validation\ValidationContext;

#[Oat\Schema(schema: 'adminCategoryIds')]
class ParentCategoryIdsData extends Data
{
    public function __construct(
        #[OAT\Property(
            type: 'array',
            items: new OAT\Items(
                type: 'integer',
            )
        )]
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
