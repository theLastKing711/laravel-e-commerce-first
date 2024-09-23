<?php

namespace App\Data\User\Auth;

use App\Services\FileService;
use App\Transformers\ToWebStoragePathTransformer;
use OpenApi\Attributes as OAT;
use Spatie\LaravelData\Attributes\WithTransformer;
use Spatie\LaravelData\Data;

#[Oat\Schema()]
class RegisterData extends Data
{
    public function __construct(
        #[OAT\Property(default: 'user')]
        public string $dial_code,
        #[OAT\Property(default: 'user')]
        public string $number,
    ) {
    }

}
