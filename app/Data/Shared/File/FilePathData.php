<?php

namespace App\Data\Shared\File;
use CloudinaryLabs\CloudinaryLaravel\Model\Media;
use OpenApi\Attributes as OAT;
use Spatie\LaravelData\Data;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
#[Oat\Schema()]
class FilePathData extends Data
{
    public function __construct(
        #[OAT\Property]
        public string $url,
    ) {
    }

    public static function fromModel(Media $media): self
    {
        return new self(
            url: $media->getSecurePath()
        );
    }
}
