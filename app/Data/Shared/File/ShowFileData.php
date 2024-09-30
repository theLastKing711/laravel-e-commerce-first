<?php

namespace App\Data\Shared\File;

use CloudinaryLabs\CloudinaryLaravel\Model\Media;
use OpenApi\Attributes as OAT;
use Spatie\LaravelData\Data;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
#[Oat\Schema()]
class ShowFileData extends Data
{
    public function __construct(
        #[OAT\Property]
        public int $uid,
        #[OAT\Property]
        public string $url,
    ) {
    }

    public static function fromModel(Media $media)
    {
        return new self(
            uid: $media->getKey(),
            url: $media->getSecurePath()
        );
    }
}
