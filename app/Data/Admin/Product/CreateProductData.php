<?php

namespace App\Data\Admin\Product;

use App\Enum\Unit;
use App\Transformers\ToBoolTransformer;
use Illuminate\Http\UploadedFile;
use OpenApi\Attributes as OAT;
use Spatie\LaravelData\Attributes\WithTransformer;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Support\Validation\ValidationContext;

#[Oat\Schema(schema: 'adminCreateProduct')]
class CreateProductData extends Data
{
    public function __construct(
        #[
            OAT\Property(type: 'string'),
        ]
        public string $name,
        #[
            OAT\Property(type: 'string', example: '00.00'),
        ]
        public string $price,
        #[
            OAT\Property(type: 'string'),
        ]
        public string $description,
        #[
            OAT\Property(type: 'boolean'),
        ]
        public bool $is_most_buy,
        #[
            OAT\Property(type: 'boolean'),
        ]
        public bool $is_active,
        #[OAT\Property()]
        public Unit $unit,
        #[
            OAT\Property(type: 'string', format: 'binary'),
        ]
        public ?UploadedFile $image
    ) {
    }

    public static function rules(ValidationContext $context): array
    {
        return [
            'price' => ['required', 'numeric', 'decimal:0,2', 'gte:0'],
            'is_most_buy' => ['required', 'string', 'in:true,false'],
            'is_active' => ['required', 'string', 'in:true,false'],
        ];
    }

}
