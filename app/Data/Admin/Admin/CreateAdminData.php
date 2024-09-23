<?php

namespace App\Data\Admin\Admin;

use App\Models\User;
use OpenApi\Attributes as OAT;
use Spatie\LaravelData\Attributes\Validation\Unique;
use Spatie\LaravelData\Data;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
#[Oat\Schema()]
class CreateAdminData extends Data
{
    public function __construct(
        #[OAT\Property(type: 'string'), Unique(User::class, 'name')]
        public string $name,
        #[OAT\Property(type: 'string'), Unique(User::class, 'email')]
        public string $password,
    ) {
    }

}
