<?php

namespace App\Data\Admin\Auth;

use App\Services\FileService;
use App\Transformers\ToWebStoragePathTransformer;
use OpenApi\Attributes as OAT;
use Spatie\LaravelData\Attributes\WithTransformer;
use Spatie\LaravelData\Data;

#[Oat\Schema(schema: 'adminLogin')]
class LoginData extends Data
{
    public function __construct(
        #[OAT\Property(type: 'string', default: 'admin')]
        public string $name,
        #[OAT\Property(type: 'string', default: 'admin')]
        public string $password,
    ) {
    }

    //    public function casts(): array
    //    {
    //        return [
    //            'image' =>
    //        ]
    //    }

}
