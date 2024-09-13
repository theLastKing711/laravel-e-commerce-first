<?php

namespace App\Data\User\Auth;

use App\Services\FileService;
use App\Transformers\ToWebStoragePathTransformer;
use OpenApi\Attributes as OAT;
use Spatie\LaravelData\Attributes\WithTransformer;
use Spatie\LaravelData\Data;

#[Oat\Schema(schema: 'userLogin')]
class LoginData extends Data
{
    public function __construct(
        #[OAT\Property(type: 'string', default: '963')]
        public string $dial_code,
        #[OAT\Property(type: 'string', default: '0968259851')]
        public string $number,
        #[OAT\Property(type: 'string', default: '123456')]
        public string $code,
    ) {
    }

}
