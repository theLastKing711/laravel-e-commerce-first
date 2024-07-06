<?php

namespace App\Data\Admin\Category;

use OpenApi\Attributes as OAT;
use Spatie\LaravelData\Data;

#[Oat\Schema(schema: 'adminCategory')]
class CategoryData extends Data
{
    public function __construct(
        #[OAT\Property(type: 'string'),
        ]
        public string $id,
        #[OAT\Property(type: 'string')]
        public string $name,
        #[OAT\Property(type: 'string')]
        public ?string $image,
        #[OAT\Property(
            type: 'string',
            format: 'datetime',
            default: '2017-02-02 18:31:45',
            pattern: 'YYYY-MM-DD'
        )]
        public string $created_at,
    ) {
    }

    //    public function casts(): array
    //    {
    //        return [
    //            'image' =>
    //        ]
    //    }

}
