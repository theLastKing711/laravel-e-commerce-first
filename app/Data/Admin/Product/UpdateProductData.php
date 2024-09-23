<?php

namespace App\Data\Admin\Product;

use App\Data\Shared\Swagger\Property\FileProperty;
use App\Enum\Unit;
use Illuminate\Http\UploadedFile;
use OpenApi\Attributes as OAT;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Support\Validation\ValidationContext;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
#[Oat\Schema(schema: 'adminUpdateProduct')]
class UpdateProductData extends Data
{
    public function __construct(
        #[OAT\Property()]
        public string $name,
        #[OAT\Property(example: '00.00')]
        public string $price,
        #[OAT\Property()]
        public string $description,
        #[OAT\Property()]
        public bool $is_most_buy,
        #[OAT\Property()]
        public bool $is_active,
        #[OAT\Property()]
        public Unit $unit,
        #[FileProperty]
        public ?UploadedFile $image
    ) {
    }

    public static function rules(ValidationContext $context): array
    {
        return [
            'price' => ['required', 'numeric', 'decimal:0,2'],
            'is_most_buy' => ['required', 'string', 'in:true,false'],
            'is_active' => ['required', 'string', 'in:true,false'],
        ];
    }
}
