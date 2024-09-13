<?php

namespace App\Data\Admin\Admin;

use App\Models\User;
use App\Services\FileService;
use App\Transformers\ToWebStoragePathTransformer;
use OpenApi\Attributes as OAT;
use Spatie\LaravelData\Attributes\Validation\Unique;
use Spatie\LaravelData\Attributes\WithTransformer;
use Spatie\LaravelData\Data;


#[Oat\Schema(schema: 'adminCreateAdmin')]
class CreateAdminData extends Data
{
    public function __construct(
        #[OAT\Property(type: 'string'), Unique(User::class, 'name')]
        public string $name,
        #[OAT\Property(type: 'string'), Unique(User::class, 'email')]
        public string $email,
        #[OAT\Property(
            type: 'string',
            format: 'datetime',
            default: '2017-02-02 18:31:45',
            pattern: 'YYYY-MM-DD'
        )]
        public string $created_at,
    ) {
    }

}
