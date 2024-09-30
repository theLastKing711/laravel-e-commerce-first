<?php

namespace App\Data\Shared\File;
use CloudinaryLabs\CloudinaryLaravel\Model\Media;
use OpenApi\Attributes as OAT;
use Spatie\LaravelData\Data;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
#[Oat\Schema()]
class CreateFilePathData extends Data
{
    public function __construct(
        #[OAT\Property]
        public string $url,
        #[OAT\Property]
        public string $public_id,
    ) {
    }

}
