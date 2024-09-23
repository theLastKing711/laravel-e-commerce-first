<?php

namespace App\Data\Shared;

use App\Transformers\ToWebStoragePathTransformer;
use OpenApi\Attributes as OAT;
use Spatie\LaravelData\Attributes\WithTransformer;
use Spatie\LaravelData\Data;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
#[Oat\Schema()]
class ListData extends Data
{
    public function __construct(
        #[OAT\Property()]
        public int $id,
        #[OAT\Property()]
        public string $title,
    ) {
    }

    public static function fromModel($item): self
    {
        return new self(
            id: $item->id,
            title: $item->name,
        );
    }

}

