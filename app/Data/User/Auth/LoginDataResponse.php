<?php

namespace App\Data\User\Auth;

use App\Models\User;
use OpenApi\Attributes as OAT;
use Spatie\LaravelData\Data;

#[Oat\Schema(schema: 'userLoginResponse')]
class LoginDataResponse extends Data
{
    public function __construct(
        #[OAT\Property(type: 'integer')]
        public int $id,
        #[OAT\Property(type: 'string')]
        public string $name,
        #[OAT\Property(type: 'string')]
        public string $email,
        #[OAT\Property(type: 'string')]
        public string $created_at,
    ) {
    }

    public static function fromModel(User $user): self
    {
        return new self(
            id: $user->id,
            name: $user->name,
            email: $user->email,
            created_at: $user->created_at
        );
    }

}
