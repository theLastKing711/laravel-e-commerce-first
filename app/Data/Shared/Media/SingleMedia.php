<?php

namespace App\Data\Shared\Media;

use App\Interfaces\Mediable;
use App\Models\Media;
use OpenApi\Attributes as OAT;
use Spatie\LaravelData\Data;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
#[Oat\Schema()]
class SingleMedia extends Data
{
    public function __construct(
        #[OAT\Property()]
        public string $id,
        #[OAT\Property()]
        public string $file_url,
    ) {}

    public static function fromModel(?Mediable $mediable): self
    {

        /** @var Media $first_media */
        $first_media = $mediable->medially->first();

        return new self(
            id: $first_media->id,
            file_url: $first_media->file_url
        );
    }
}
